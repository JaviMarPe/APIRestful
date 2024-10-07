#!/bin/bash
sudo chown -R $USER:$USER src
sudo find src -type f -exec chmod 644 {} \;
sudo find src -type d -exec chmod 755 {} \;
sudo chgrp -R www-data src/storage src/bootstrap/cache
sudo chmod -R ug+rwx src/storage src/bootstrap/cache