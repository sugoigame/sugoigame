<?php $news = $connection->run("SELECT *, unix_timestamp(data) AS timestamp FROM tb_news_coo ORDER BY data DESC LIMIT 6")->fetch_all_array(); ?>
<?php if ($news) : ?>
    <li id="div_news_coo" class="div_icon" data-toggle="popover" data-trigger="focus" data-placement="bottom"
        data-html="true" data-content='
            <?php foreach ($news as $new) : ?>
                <div>
                    •
                    <small>
                        <?= date("d/m/Y - h:i", $new["timestamp"]); ?>:
                    </small>
                    <span>
                        <?= $new["msg"]; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        '>
        <a href="#" class="noHref">
            <span class="hidden-sm hidden-xs hidden-md">
                <small>
                    <?= date("d/m/Y - h:i", $news[0]["timestamp"]); ?>:
                </small>
                <?= $news[0]["msg"] ?>
            </span>
            <img src="Imagens/news.png" />
        </a>
    </li>
<?php endif; ?>

