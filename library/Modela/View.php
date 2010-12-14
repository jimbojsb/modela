<?php
class Modela_View
{
    // these are public properties to simplify json-ification to CouchDB's view specs
    public $map;
    public $reduce;
    
    /**
     * recreate / update view definitions given a well-structured
     * directory on disk that contains map and reduce javascript files
     * @param string $viewsPath
     */
    public static function loadViews($viewsPath)
    {
        // iterate folders under the patht that contain map and reduce functions
        // each folder becomes the name of a design document
        $diDesignDocs = new DirectoryIterator($viewsPath);
        foreach ($diDesignDocs as $diDesignDoc) {

            // only process folders, ignore whatever else may be lying around
            if ($diDesignDoc->isDir() && !$diDesignDoc->isDot()) {
                $designDoc = new Modela_Doc_Design();
                $designDoc->_id = $diDesignDoc->getFilename();
                $designDoc->language = "javascript";
                
                // iterate over the files within that folder, looking for *.map.js
                // and *.reduce.js, storing what we find to a temporary array
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
                
                // process the temporary array tha result from the file scan
                // into proper Modela_View objects
                foreach ($views as $view => $functions) {
                    $viewObj = new self();
                    foreach ($functions as $name => $code) {
                        $viewObj->$name = $code;
                    }
                    $designDoc->addView($view, $viewObj);
                }
                
                // if the design doc already exists, get it's version number 
                // so we can save over it
                $docExists = Modela_Doc::get($designDoc->_id);
                if ($docExists->_rev) {
                    $designDoc->_rev = $docExists->_rev;
                }
                $designDoc->save();
            }
        }
    }    
}