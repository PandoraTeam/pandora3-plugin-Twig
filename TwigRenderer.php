<?php
namespace Pandora3\Plugins\Twig;

use Pandora3\Core\Interfaces\RendererInterface;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * Class TwigRenderer
 * @package Pandora3\Plugins\Twig
 */
class TwigRenderer implements RendererInterface {
	
	/** @var Environment $twig */
	protected $twig;
	
	/**
	 * @param string $path
	 * @param array $options
	 */
	public function __construct(string $path, array $options = []) {
		$this->twig = new Environment(new FilesystemLoader(APP_PATH.'/Views'), $options);
		$this->twig->addTokenParser(new RenderTokenParser);
		$this->twig->addExtension(new JsExtension);
	}

	/**
	 * @return Environment
	 */
	public function getEnvironment(): Environment {
		return $this->twig;
	}

	/**
	 * @param ExtensionInterface[] $extensions
	 */
	public function addExtensions(array $extensions): void {
		foreach ($extensions as $extension) {
			$this->twig->addExtension($extension);
		}
	}

	/**
	 * @param array $functions
	 */
	public function addFunctions(array $functions): void {
		foreach ($functions as $name => $function) {
			$this->twig->addFunction(new TwigFunction($name, $function));
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function render(string $viewPath, array $context = []): string {
		$viewPath = preg_replace('#(\.twig)?$#', '.twig', $viewPath, 1);
		try {
			return $this->twig->render($viewPath, $context);
		} catch (\Throwable $ex) {
			throw new \RuntimeException("Rendering view '$viewPath' failed", E_WARNING, $ex);
		}
	}
	
}