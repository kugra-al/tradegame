<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Storage;

class Desktop extends Model
{
    public static function getProgramsForUser($user) {
    	$programs = array(
			'text-editor'=>array('id'=>1,'name'=>'Text Editor','slug'=>'text-editor','icon'=>'fa fa-file-alt','toolbar'=>'default','statusbar'=>'default','cpu'=>10),
			'settings'=>array('id'=>2,'name'=>'Settings','slug'=>'settings','icon'=>'fa fa-cog','max_instances'=>1,'cpu'=>10),
			'web-browser'=>array('id'=>3,'name'=>'Web Browser','slug'=>'web-browser','icon'=>'fas fa-globe','statusbar'=>'default','cpu'=>30),
            'file-explorer'=>array('id'=>4,'name'=>'File Explorer','slug'=>'file-explorer','icon'=>'fa fa-folder-open','cpu'=>10),
            'task-manager'=>array('id'=>5,'name'=>'Task Manager','slug'=>'task-manager','icon'=>'fa fa-file','max_instances'=>1,'cpu'=>10),
            'image-editor'=>array('id'=>6,'name'=>'Image Editor','slug'=>'image-editor','icon'=>'fa fa-file-image','cpu'=>20),
		);
    	return $programs;
    }

    public static function processAction($cmd, $args, $forms = array(), $user) {
    	$out = array();
    	switch($cmd) {
    		case 'web-browser' : 	$url = null;
                                    if (is_array($forms) && sizeof($forms)) {
        								foreach($forms as $f) {
        									if (isset($f['url']))
    	       									$url = $f['url'];
    			     					}
    				                }
                    				if ($url) {
    									$out['url'] = $url;
    									$out['contents'] = file_get_contents($url);
    								}
    								break;
            case 'file-explorer' :  $path = $user->id."/";
            						if (is_array($forms) && sizeof($forms)) {
                                        foreach($forms as $f) {
                                            if (isset($f['action'])) {
                                                switch($f['action']) {
                                                    case 'create'   :   
                                                        if (isset($f['type']) && isset($f['name'])) {
                                                            $type = $f['type'];
                                                            if ($type == 'dir')
                                                                Storage::drive('user')->makeDirectory($path.$f['name']);
                                                        }
                                                    break;
                                                    case 'list'  :
                                                    	if (isset($f['path']))
                                                    		$path = $path.$f['path'];
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    $files = Desktop::getFilesForUser($user,$path);
                                    $out['files'] = $files;
                                    break;
            case 'settings'     :   
                                    if (isset($args['view'])) {
                                        switch($args['view']) {
                                            case 'system-info'  :   $out['stats'] = array('user'=>$user->id,'cpu'=>1,'ram'=>'14');
                                                                    break;  
                                            case 'background'	:	$out['color'] = Desktop::getSettingsForUser($user,'desktop.css.background');
                                            						break;
                                        }
                                    }
                                    break;
            case 'text-editor'	:
            case 'image-editor' :
            						if (isset($args['action'])) {
            							switch($args['action']) {
            								case 'open' :
            									$file = '';
            									if (is_array($forms) && sizeof($forms)) {
            										foreach($forms as $f) {
            											if (isset($f['path']))
            												$file = $f['path'];
            											if (isset($f['file']))
            												$file .= $f['file'];
            										}
            									}
            									if (strlen($file)) {
            										$data = Storage::drive('user')->get($user->id."/".$file);
                                                    if(strpos($file,'.jpg')!==false) // do this with mime type instead
                                                        $data = "data:image/jpg;base64, ".base64_encode($data);
            										$out['output'] = $data;
            									}
            									$out['file'] = $file;
                                                $out['status'] = "File: $file";
            									break;
            							}
            						}
            						break;
    		default 			:	$out['error'] = 'unknown cmd';
    								break;
    	}

    	return $out;
    }

    public static function getSettingsForUser($user,$setting = false) {
    	$settings = array(
    		'desktop'	=> array(
    			'css' => array(
	    			'background' => '#CCCCCC',
    			)
    		)
    	);
    	$out = $settings;
    	if ($setting) {
    		$setting = explode(".",$setting);
    		if (isset($settings[$setting[0]])) {
    			if (isset($settings[$setting[0]][$setting[1]])) {
    				if (isset($settings[$setting[0]][$setting[1]][$setting[2]]))
    				$out = $settings[$setting[0]][$setting[1]][$setting[2]];
    			}
    		}
    	}
    	return $out;
    }

    public static function getFilesForUser($user, $path = '') {
        if (!$user || !isset($user->id))
            return array();
       // $path = $user->id.'/';
        $path = str_replace("..","",$path);
        $dirs = Storage::drive('user')->directories($path);
        $files = Storage::drive('user')->files($path);
        $tmp = array();
        foreach($dirs as $i=>$d) {
        	$realpath = storage_path("app/user/$d");
        	$stat = stat($realpath);
        	$dirs[$i] = array(
        		'name'=>str_replace(array($path,"/"),"",$d),
        		'file'=>str_replace("1/","",$d),
        		'size'=>'<DIR>',
        		'time'=>\Carbon\Carbon::createFromTimestamp($stat['mtime']));
        }
        if ($path !== $user->id."/") {
        	$pathAbove = explode("/",$path);
        	unset($pathAbove[sizeof($pathAbove)-1]);
        	//array_unshift($pathAbove);
        	if (sizeof($pathAbove) < 2)
        		$pathAbove = array("..");
        	else
        		array_shift($pathAbove);
        	$pathAbove = implode("/",$pathAbove);
	        array_unshift($dirs,array('file'=>$pathAbove,'name'=>'..','size'=>'<DIR>','time'=>''));
        }
        foreach($files as $i=>$f) {
        	$realpath = storage_path("app/user/$f");
        	$stat = stat($realpath);
            $mime = mime_content_type($realpath);
            switch($mime) {
                case 'image/jpeg'       :   $program = 'image-editor';
                                            $icon = 'fa fa-file-image';
                                            break;
                default                 :   $program = 'text-editor';
                                            $icon = 'fa fa-file-alt';
                                            break;
            }
        	$files[$i] = array(
        		'name'=>str_replace(array($path,"/"),"",$f),
        		'file'=>str_replace($path,"",$f),
        		'size'=>Desktop::formatBytes($stat['size']),
        		'time'=>\Carbon\Carbon::createFromTimestamp($stat['mtime']),
                'mime'=>$mime,
                'program'=>$program,
                'icon'=>$icon,
            );
        }
        $path = str_replace("1/","",$path);
        return array('dir'=>$dirs,'file'=>$files,'path'=>$path);
    }

/**
     * Format bytes to kb, mb, gb, tb
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}
