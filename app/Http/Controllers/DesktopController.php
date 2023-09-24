<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Desktop;
use Auth;

class DesktopController extends Controller
{
    public function index() {
    	$programs = Desktop::getProgramsForUser(Auth::user());
    	$settings = Desktop::getSettingsForUser(Auth::user());
    	return view ('game',array('programs'=>$programs,'settings'=>$settings));
    }

    public function postIndex(Request $request) {
    	$cmd = $request->get('cmd');
    	$args = $request->get('args');

    	$errors = array();
    	$html = '';
    	if (!$cmd)
    		$errors[] = "no cmd";
    	if (!$args)
    		$errors[] = "no args";
    	$settings = array();

    	$toolbar = '';
        $statusbar = '';
		$programs = Desktop::getProgramsForUser(Auth::user());
    	switch($cmd) {
    		case "run"		:	$view = 'programs.';
                                $program = 'prorgam';
    							if(isset($args['program'])) {
                                    $program = $args['program'];
    								$view = $view.$args['program'];
                                }

    							if (isset($args['view']) && (isset($args['program']) && $args['program'] !== $args['view']))
    								$view = $view.".".$args['view'];

                                $results = Desktop::processAction($args['program'],$args,$request->get('forms'),Auth::user());                                
    							if (view()->exists($view)) {
    								$view = view($view,$results);
    								$html = $view->render();
									if (isset($programs[$args['program']]['toolbar'])) {
										$toolview = 'programs.toolbars.'.$programs[$args['program']]['toolbar'];
										if (view()->exists($toolview)) {
											$toolview = view($toolview);
											$toolbar = $toolview->render();
										}
									}
                                    if (isset($programs[$args['program']]['statusbar'])) {
                                        $status = "Ready";
                                        if (isset($results['status']))
                                            $status = $results['status'];
                                        $statusbar = "<b>$status</b>";
                                    }
    							} else {
    								$errors[] = 'no view found';
    							}
    							break;
    		case "settings"	:	
    							$action = $args['action'];
    							switch($action) {
    								case 'save' : 	
    									if (isset($request->get('forms')[0]['background'])) {
    										$settings = Desktop::getSettingsForUser(Auth::user());
    										$settings['desktop']['css']['background'] = $request->get('forms')[0]['background'];
    									}
    									break;

    							}
    							$view = 'programs.settings.';
    							
    							if (isset($args['view'])) {
    								switch($args['view']) {
    									case 'index' : 	$view = 'programs.settings';
    													break;
    									default 	 :  $view = $view.$args['view'];
    													break;
    								}
    							}
                                $results = Desktop::processAction($cmd,$args,$request->get('forms'),Auth::user());
    							if (view()->exists($view)) {
    								$view = view($view,$results);
    								$html = $view->render();
    							}
    							break;
    		default : 	
    					if (isset($programs[$cmd])) {
    						$forms = $request->get('forms');
    						$results = Desktop::processAction($cmd,$args,$forms,Auth::user());
							$view = 'programs.';
							if(isset($args['program']))
								$view = $view.$args['program'];
							//return $view;
    						if (isset($args['view'])) {
								// if ($args['view'] == 'index')
								// 	$view = 'programs.settings';
								// else
								// 	$view = $view.$args['view'];
							}
							//return $view;
							//dd($view);
							if (view()->exists($view)) {
								$view = view($view,$results);
								$html = $view->render();
							}
							if (isset($programs[$cmd]['toolbar'])) {
								$toolview = 'programs.toolbars.'.$programs[$cmd]['toolbar'];
								if (view()->exists($toolview)) {
									$toolview = view($toolview);
									$toolbar = $toolview->render();
								}
							}
                            if(isset($programs[$cmd]['statusbar'])) {
                                $status = 'Ready';
                                if (isset($results['status']))
                                    $status = $results['status'];
                                $statusbar = "<b>$status</b>";
                            }
							//dd($html);
    								
    					} else
    						$errors[] = "unknown cmd: $cmd";
    					break;
    	}

        //$statusbar = "t";
    	$out = array('request'=>$request->all(),'errors'=>$errors,'html'=>$html,'toolbar'=>$toolbar,'statusbar'=>$statusbar);
    	if (sizeof($settings))
    		$out['settings'] = $settings;

    	return $out;
    }
}
