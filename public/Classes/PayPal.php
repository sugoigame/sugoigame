<?php
class PayPal {
	private		$use_sandbox	= FALSE,
				$fields			= [],
				$ipn_response 	= '',
				$ipn_log		= TRUE,
				$log_file_dir 	= '',
				$last_error		= '';

	public	$ipn_data			= [];

	const VERIFY_URI            = 'https://ipnpb.paypal.com/cgi-bin/webscr';
	const SANDBOX_VERIFY_URI    = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

	const PAYPAL_URI            = 'https://www.paypal.com/cgi-bin/webscr';
	const SANDBOX_PAYPAL_URI    = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

	const VALID     			= 'VERIFIED';
	const INVALID   			= 'INVALID';

	public function __construct() {
		$this->log_file_dir = dirname(dirname(__FILE__)) . '/Logs/PayPal';
	}

	public function useSandbox() {
		$this->use_sandbox = TRUE;
	}

	public function getPayPalUri() {
		if ($this->use_sandbox) {
			return self::SANDBOX_PAYPAL_URI;
		} else {
			return self::PAYPAL_URI;
		}
	}

	public function getPayPalIPNUri() {
		if ($this->use_sandbox) {
			return self::SANDBOX_VERIFY_URI;
		} else {
			return self::VERIFY_URI;
		}
	}

	public function addField($field, $value) {
		$this->fields[$field] = $value;
	}

	public function submitPayment() {
		$this->addField('rm',   '2');
		$this->addField('cmd',  '_xclick'); 

		echo "<html>\n";
		echo "<head><meta charset=\"utf-8\" /><title>Processing Payment...</title></head>\n";
		echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
		echo "<center><h2>Please wait, your order is being processed and you";
		echo " will be redirected to the paypal website.</h2></center>\n";
		echo "<form method=\"post\" name=\"paypal_form\" ";
		echo "action=\"" . $this->getPayPalUri() . "\">\n";

		foreach ($this->fields as $name => $value) {
			echo "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\" />\n";
		}
		echo "<center><br /><br />If you are not automatically redirected to ";
		echo "paypal within 5 seconds...<br /><br />\n";
		echo "<input type=\"submit\" value=\"Click Here\" /></center>\n";

		echo "</form>\n";
		echo "</body></html>";
	}

	public function dumpFields() {
		echo "<h3>PayPal->dumpFields() Output:</h3>";
		echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
				<tr>
					<td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
					<td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
				</tr>"; 

		ksort($this->fields);
		foreach ($this->fields as $key => $value) {
			echo "<tr><td>{$key}</td><td>" . urldecode($value) . "</td></tr>";
		}

		echo "</table><br />"; 
	}

	public function verifyIPN() {
		if (!count($_POST)) {
			$this->last_error = "Missing POST Data";
			$this->logResults(FALSE);

			throw new Exception($this->last_error);
		}

		$raw_post_data  = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost         = [];
		foreach ($raw_post_array as $keyval) {
			$keyval = explode('=', $keyval);
			if (count($keyval) == 2) {
				// Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
				if ($keyval[0] === 'payment_date') {
					if (substr_count($keyval[1], '+') === 1) {
						$keyval[1] = str_replace('+', '%2B', $keyval[1]);
					}
				}
				$myPost[$keyval[0]] = urldecode($keyval[1]);
			}
		}

		// Build the body of the verification post request, adding the _notify-validate command.
		$req = 'cmd=_notify-validate';
		foreach ($myPost as $key => $value) {
			$this->ipn_data[$key] = $value;

			$value = urlencode($value);
			$req .= "&{$key}={$value}";
		}

		// Post the data back to PayPal, using curl. Throw exceptions if errors occur.
		$ch = curl_init($this->getPayPalIPNUri());
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'User-Agent: PHP-IPN-Verification-Script',
			'Connection: Close',
		));
		$this->ipn_response = curl_exec($ch);
		if (!($this->ipn_response)) {
			$errno  = curl_errno($ch);
			$errstr = curl_error($ch);
			curl_close($ch);

			$this->last_error = "cURL error: [{$errno}] {$errstr}";
			$this->logResults(FALSE);

			throw new Exception($this->last_error);
		}

		$info       = curl_getinfo($ch);
		$http_code  = $info['http_code'];
		if ($http_code != 200) {
			$this->last_error = "PayPal responded with http code {$http_code}";
			$this->logResults(FALSE);

			throw new Exception($this->last_error);
		}

		curl_close($ch);

		// Check if PayPal verifies the IPN data, and if so, return true.
		if ($this->ipn_response == self::VALID) {
			$this->logResults(TRUE);

			return TRUE;
		} else {
			$this->logResults(FALSE);

			return FALSE;
		}
	}

	private function logResults($success) {
		if (!$this->ipn_log)
			return;  // is logging turned off?

		$logText	= '';
		$logDate	= date('Y-m-d H:i:s');

		// date and POST url
		for ($i=0; $i<90; $i++) { $logText .= '-'; }
		$logText .= "\n[{$logDate}] - {$this->getPayPalIPNUri()} | " . (!$success ? 'FAIL: ' . $this->last_error : 'SUCCESS') . "!\n";

		// HTTP Response
		for ($i=0; $i<90; $i++) { $logText .= '-'; }
		$logText .= "\n{$this->ipn_response}\n";

		// POST vars
		for ($i=0; $i<90; $i++) { $logText .= '-'; }
		$logText .= "\n";
		foreach ($this->ipn_data as $key => $value) {
			$logText .= str_pad($key, 25) . "{$value}\n";
		}
		$logText .= "\n";

		// Write to log
		file_put_contents(
			$this->log_file_dir . "/IPN-{$logDate}.log",
			$logText,
			FILE_APPEND
		);
	}
}