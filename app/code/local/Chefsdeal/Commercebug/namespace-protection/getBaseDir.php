<?php        
    $type = $type ? $type : \Magento\App\Dir::ROOT;
    $dir = $this->getObjectManager()->get('Magento\App\Dir');
    return $dir->getDir($type);