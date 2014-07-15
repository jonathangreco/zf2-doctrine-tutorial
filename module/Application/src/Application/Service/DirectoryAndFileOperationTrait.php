<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * Ce Trait sert a regrouper les fonctions d'opérations sur les
 * fichiers, dossiers etc..
 */
namespace Application\Service;

trait DirectoryAndFileOperationTrait
{
        /**
     * Création d'un répertoire ou d'une arborescence de répertoires
     * $dirpathFromRoot : Chemin vers le répertoire à créer depuis la racine
     * $chmod : Niveau de droit d'accès au(x) répertoire(s)
     * 
     * @param string $dirpathFromRoot
     * @param int $chmod
     * @return bool
     */
    public function createDir($dirpathFromRoot, $chmod = 0777)
    {
        $dirpathFromRoot = $this->fixpath($dirpathFromRoot);
        if (is_dir($dirpathFromRoot) && is_writable($dirpathFromRoot))
            return true;
        if (!mkdir($dirpathFromRoot, $chmod, true))
            return false;
        $dirs = explode('/', $dirpathFromRoot);
        $full_dir_path = '';
        foreach ($dirs as $k=>$v) 
        {
            if (empty($v)) 
                continue;
            $full_dir_path .= '/'.$v;
            if (file_exists('/'.$full_dir_path)) 
                chmod('/'.$full_dir_path, $chmod);
        }
        return true;
    }

    /**
     * Récupération d'une liste de fichiers / répertoires contenus dans un dossier
     * $dirpathFromRoot : Chemin vers le répertoire à lister depuis la racine
     * $sortColumn : Colonne de tri du listing de fichiers (valeurs possibles : filename, extension, path, size, ctime, mtine)
     * $orderBy : Ordre de tri du listing de fichiers (valeurs possibles : SORT_ASC || SORT_DESC)
     * $listDir : Si true, on liste aussi les répertoires
     * $recursive : Si true, le listing s'exerce de façon récursive
     * 
     * @param string $dirpathFromRoot
     * @param string $sortColumn
     * @param const $orderBy SORT_ASC || SORT_DESC
     * @param  boolean $listDir
     * @param  boolean $recursive
     * @return array
     */
    public function listDir($dirpathFromRoot, $sortColumn = 'filename', $orderBy = SORT_ASC, $listDir = false, $recursive = false)
    {
        if ($orderBy!=SORT_ASC && $orderBy!=SORT_DESC)
            $orderBy = SORT_ASC;
        $rs = $this->recursiveScandir($dirpathFromRoot, $recursive);
        $files = array();
        foreach ($rs as $file)
        {
            if ((!$listDir && !is_dir($file)) || $listDir)
            {
                $infos = pathinfo($file);
                $infos_plus = stat($file);
                if (is_dir($file))
                    $infos['extension'] = '';
                $files[] = array(
                    'filename' => $infos['filename'],
                    'extension' => $infos['extension'],
                    'path' => $file,
                    'size' => $infos_plus['size'],
                    'ctime' => $infos_plus['ctime'],
                    'mtime' => $infos_plus['mtime']
                );
            }
        }
        foreach ($files as $index => $row) 
            foreach ($row as $key => $value) 
                ${$key}[$index] = $value;
        if (count($files)>1)
        {
            if (isset(${$sortColumn}) && is_int(${$sortColumn}[0]))
                array_multisort(${$sortColumn}, $orderBy, SORT_NUMERIC, $filename, SORT_ASC, SORT_STRING, $files);
            elseif (isset($$sortColumn) && $sortColumn!='filename')
                array_multisort(${$sortColumn}, $orderBy, SORT_STRING, $filename, SORT_ASC, SORT_STRING, $files);
            else
                array_multisort($filename, SORT_ASC, SORT_STRING, $files);
        }
        return $files;
    }

    /**
     * Scandir récursif pour lister les fichiers et dossiers des sous-dossier
     * 
     * @param string $dirpathFromRoot
     * @param boolean $recursive
     * @param array $data
     * @return array
     */
    private function recursiveScandir($dirpathFromRoot, $recursive = false, &$data = array())
    {
        $dirpathFromRoot = $this->fixpath($dirpathFromRoot);
        $array = scandir($dirpathFromRoot);
        foreach ($array as $value)
        {
            if (!preg_match('#^[\._:]#', $value))
            {
                if (is_dir($dirpathFromRoot.$value))
                {
                    $data[] = $dirpathFromRoot.$value.'/';
                    if ($recursive)
                        $data = $this->rscandir($dirpathFromRoot.$value.'/', $recursive, $data);
                }
                elseif (is_file($dirpathFromRoot.$value))
                    $data[] = $dirpathFromRoot.$value;
            }
        }
        return $data;
    }

    /**
     * Suppresion d'un fichier
     * $filepathFromRoot : Chemin vers le fichier à supprimer depuis la racine
     * 
     * @param string $filepathFromRoot
     * @return bool
     */
    public function deleteFile($filepathFromRoot)
    {
        $dirpathFromRoot = $this->fixpath($filepathFromRoot);
        return unlink($filepathFromRoot);
    }

    /**
     * Suppresion d'un dossier
     * $dirpathFromRoot : Chemin vers le dossier à supprimer depuis la racine
     * 
     * @param string $dirpathFromRoot
     * @return bool
     */
    public function deleteDir($dirpathFromRoot)
    {
        $dirpathFromRoot = $this->fixpath($dirpathFromRoot);
        $files = array_diff(scandir($dirpathFromRoot), array('.','..')); 
        foreach ($files as $file) 
            (is_dir($dirpathFromRoot.$file)) ? $this->deleteDir($dirpathFromRoot.$file) : $this->deleteFile($dirpathFromRoot.$file);
        return rmdir($dirpathFromRoot);
    }

    /**
     * Lecture du contenu d'un fichier
     * $filepathFromRoot : Chemin vers le fichier à lire depuis la racine
     * Retourne le contenu du fichier sous forme de chaine ou false si une erreur survient
     * 
     * @param string $filepathFromRoot
     * @return mixed
     */
    public function readFile($filepathFromRoot)
    {
        $filepathFromRoot = $this->fixpath($filepathFromRoot);
        if (!is_file($filepathFromRoot))
        {
            $this->log("Tentative d'ouverture d'un fichier introuvable : ".$filepathFromRoot, false);
            return false;
        }
        if ($handle = fopen($filepathFromRoot, 'r'))
        {
            $str = fread($handle, filesize($filepathFromRoot));
            fclose($handle);
            return $str;
        }
        return false;
    }

    /**
     * Création d'un fichier
     * $content : Contenu du fichier
     * $filename : Nom du fichier
     * $dirpathFromRoot : Chemin vers le dossier conteneur du fichier
     * $rewrite : true si l'on souhaite écraser le fichier éventuellement existant
     * $chmod : Niveau de droit d'accès au(x) répertoire(s)
     * 
     * @param string $content
     * @param string $filename
     * @param string $dirpathFromRoot
     * @param bool $rewrite
     * @param int $chmod
     * @return bool
     */
    public function writeFile($content, $filename, $dirpathFromRoot, $rewrite = true, $chmod = 0777)
    {
        $fopen_type = 'a+';
        if ($rewrite)
            $fopen_type = 'w+';
        $dirpathFromRoot = $this->fixpath($dirpathFromRoot);
        if (!$this->createDir($dirpathFromRoot))
        {
            $this->log("Echec de création du répertoire : ".$dirpathFromRoot, false);
            return (false);
        }
        if (!$file = fopen($dirpathFromRoot.$filename, $fopen_type))
        {
            $this->log("Echec d'ouverture du fichier : ".$dirpathFromRoot.$filename, false);
            return (false);
        }
        if (!fwrite($file, $content))
        {
            $this->log("Echec d'écriture du fichier : ".$dirpathFromRoot.$filename, false);
            return (false);
        }
        fclose($file);
        if (!chmod($dirpathFromRoot.$filename, $chmod))
        {
            $this->log("Impossible de modifier les droits d'accès au fichier : ".$dirpathFromRoot.$filename, false);
            return (false);
        }
        return (true);
    }


    /**
     * Remplace les occurences du caractère \ et le transforme en /
     * 
     * @param string $path
     * @return string
     */
    public function fixpath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path);
        if (file_exists($path) && !is_file($path)) 
            if (substr($path, 0, -1)!='/') 
                $path .= '/';
        return ($path);
    }
}