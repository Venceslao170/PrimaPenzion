<div class="menu">
    <ul>
        <?php
        foreach ($poleStranek AS $stranka) {
            echo "<li><a href='{$stranka->getId()}'>{$stranka->getMenu()}</a></li>";
        }
        ?>
    </ul>
</div>