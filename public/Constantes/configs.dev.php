<?php
/* MySQL DATABASE */
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sugoi_v2');

/* OCEANO */
define('OCEANO_SERVER', 'localhost:9000');

/* PAGSEGURO */
define('PS_ENV', 'sandbox');    // production, sandbox
define('PS_EMAIL', 'felipe.fmedeiros95@gmail.com');
define('PS_TOKEN_SANDBOX', 'C43E8E781D194CAE9E6523999B98DCDE');
define('PS_TOKEN_PRODUCTION', null);

/* STRIPE */
define('STRIPE_TOKEN_PUBLIC', 'pk_test_51P3g3E2MOJ9VSpoai5LwI4JkUndBVEcFqkvUYK7AqocCYAQspnH1hGkx0bBFjUIbQXL5jllocNkUz8ePA7h4ecqD00okPWh5jW');
define('STRIPE_TOKEN_SECRET', 'sk_test_51P3g3E2MOJ9VSpoadpgExIOlGblp1sBn4jD0AFjnyGpARmQl6CMMHtncENAmpTBz53OywIKsHLGQCorGKE8gdpjj00MK4dFfv2');
define('STRIPE_CLI_WEBHOOK', 'whsec_8e31f770c3c6959efaaafc56e15b8b248cd178232f9e3cc4d3424ebf13afa7e9');
