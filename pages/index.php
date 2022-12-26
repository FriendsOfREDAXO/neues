<?php

echo rex_view::title(rex_addon::get('neues')->getProperty('page')['title']);
rex_be_controller::includeCurrentPageSubPath();
