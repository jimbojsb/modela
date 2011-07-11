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
        $viewData = array();
        $files = new DirectoryIterator($viewsPath);
        foreach ($files as $file) {
            if ($file->isFile()) {
                $parts = explode('.', $file->getFilename());
                $view = $parts[1];
                $designDoc = $parts[0];
                $content = file_get_contents($file->getPathName());
                $contentType = $parts[2];
                $viewData[$designDoc][$view][$contentType] = $content;
            }
        }
        foreach ($viewData as $designDoc => $views) {
            $dd = new Modela_Doc_Design();
            $dd->_id = $designDoc;
            foreach ($views as $viewName => $functions) {
                $v = new Modela_View();
                $v->map = $functions['map'];
                $v->reduce = $functions['reduce'];
                $dd->addView($viewName, $v);
            }
            $docExists = Modela_Doc::get($dd->_id);
            if ($docExists) {
                $dd->_rev = $docExists->_rev;
            }
            $dd->save();
        }
    }    
}