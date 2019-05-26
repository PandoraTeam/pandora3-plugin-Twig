<?php
namespace Pandora3\Plugins\Twig;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

/**
 * Class RenderNode
 * @package Pandora3\Plugins\Twig
 */
class RenderNode extends Node {

	public function __construct(AbstractExpression $value, $line, $tag = null) {
		parent::__construct(['value' => $value], [], $line, $tag);
	}

	public function compile(Compiler $compiler) {
		$compiler
			->addDebugInfo($this)
			->write('echo ')
			->subcompile($this->getNode('value'))
			->raw(";\n");
	}

}