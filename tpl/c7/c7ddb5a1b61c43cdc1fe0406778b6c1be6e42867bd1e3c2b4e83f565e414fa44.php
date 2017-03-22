<?php

/* user.html */
class __TwigTemplate_951d9774cc60c753d03de6092c9f2a44591a6c8e74b3abd83242e187470fb676 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("main.html", "user.html", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "main.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo " Test ";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "  
  <div class=\"site-error\">
    <p style=\"font-size: 25px;\"><span style=\"font-size: 100px; margin-right: 40px; margin-left: 20px;\">;(</span>";
        // line 8
        echo ($context["msg"] ?? null);
        echo "</p>
    <p></p>
  </div>
  
";
    }

    public function getTemplateName()
    {
        return "user.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 8,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "user.html", "/Users/wangyong/www/vino/templates/user.html");
    }
}
