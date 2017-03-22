<?php

/* index.html */
class __TwigTemplate_7fa93ac26a420a7c187947d18a9b45560691697da7a32326fa4602d1e752348e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\">
    <title>";
        // line 5
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta http-equiv=\"Pragma\" content=\"no-cache\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, ($context["JS_CSS_DOMAIN"] ?? null), "html", null, true);
        echo "/css/style.css\">
</head>
<body>
<div class=\"container\">
    <img src=\"";
        // line 12
        echo twig_escape_filter($this->env, ($context["JS_CSS_DOMAIN"] ?? null), "html", null, true);
        echo "/img/logo.png\">
    <h1 class=\"title\">";
        // line 13
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</h1>
    <p>非常感谢您选择了vino，下面就开始您的vino之旅吧！</p>
    <p>技术交流或合作，欢迎与我联系qq531532957</p>
    <p>Created By <span><a href=\"https://jacoobwang.github.io\">";
        // line 16
        echo twig_escape_filter($this->env, ($context["author"] ?? null), "html", null, true);
        echo "</a></span></p>
    <p><a target=\"_blank\" href=\"https://jacoobwang.github.io/vino/vino.html\">了解更多</a></p>
    <p><a href=\"/adminhome\">这里展示一个简单的demo，欢迎查看</p>
</div>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 16,  42 => 13,  38 => 12,  31 => 8,  25 => 5,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "index.html", "/Users/wangyong/www/vino/templates/index.html");
    }
}
