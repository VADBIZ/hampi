<?php
namespace Template;
final class Twig {
	private $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($filename, $code = '') {
		if (!$code) {
			$file = DIR_TEMPLATE . $filename . '.twig';

			if (defined('DIR_CATALOG') && is_file(DIR_MODIFICATION . 'admin/view/template/' . $filename . '.twig')) {	
                $code = file_get_contents(DIR_MODIFICATION . 'admin/view/template/' . $filename . '.twig');
            } elseif (is_file(DIR_MODIFICATION . 'catalog/view/theme/' . $filename . '.twig')) {
                $code = file_get_contents(DIR_MODIFICATION . 'catalog/view/theme/' . $filename . '.twig');
            } elseif (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $file . '!');
				exit();
			}
		}

		// initialize Twig environment
		$config = array(
			'autoescape'  => false,
			'debug'       => true,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		);

		try {
			//
  // OCFilter start
  $loader_array = new \Twig\Loader\ArrayLoader(array($filename . '.twig' => $code));
  $loader_filesystem = new \Twig_Loader_Filesystem(DIR_TEMPLATE);
        
  $loader = new \Twig_Loader_Chain(array($loader_array, $loader_filesystem));
  // OCFilter end
      

			$loader1 = new \Twig_Loader_Array(array($filename . '.twig' => $code));
            $loader2 = new \Twig_Loader_Filesystem(array(DIR_TEMPLATE)); // to find further includes
            $loader = new \Twig_Loader_Chain(array($loader1, $loader2));

			$twig = new \Twig\Environment($loader, $config);

			return $twig->render($filename . '.twig', $this->data);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $filename . '!');
			exit();
		}	
	}	
}
