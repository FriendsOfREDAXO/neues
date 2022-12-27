<?php

/* Tablesets aktualisieren */

rex_yform_manager_table_api::importTablesets(rex_file::get(rex_path::addon(rex_addon::get("neues")->getName(), 'install/tableset.json')));
