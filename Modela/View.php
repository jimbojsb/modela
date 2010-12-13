<?php
class Modela_View
{
    public $map;
    public $reduce;
    
    public static function reloadViews($viewsPath)
    {
        $diDesignDocs = new DirectoryIterator($viewsPath);
        foreach ($diDesignDocs as $diDesignDoc) {
            if ($diDesignDoc->isDir() && !$diDesignDoc->isDot()) {
                $designDoc = new Modela_Doc_Design();
                $designDoc->_id = $diDesignDoc->getFilename();
                $designDoc->language = "javascript";
                $diComponents = new DirectoryIterator($diDesignDoc->getPathName());
                $views = array();
                foreach ($diComponents as $diComponent) {
                    if ($diComponent->isFile()) {
                        $filename = $diComponent->getFilename();
                        $parts = explode('.', $filename);
                        $viewName = $parts[0];
                        if (strpos($filename, '.map.js')) {
                            $views[$viewName]["map"] = file_get_contents($diComponent->getPathName());    
                        } else if (strpos($filename, '.reduce.js')) {
                            $views[$viewName]["reduce"] = file_get_contents($diComponent->getPathName());      
                        }
                    }
                }
                foreach ($views as $view => $functions) {
                    $viewObj = new self();
                    foreach ($functions as $name => $code) {
                        $viewObj->$name = $code;
                    }
                    $designDoc->addView($view, $viewObj);
                }
                $docExists = Modela_Doc::get($designDoc->_id);
                if ($docExists->_rev) {
                    $designDoc->_rev = $docExists->_rev;
                }
                $designDoc->save();
            }
        }
    }    
}