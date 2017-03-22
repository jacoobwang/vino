<?php

/* main.html */
class __TwigTemplate_50b6df1994826cbb73b7bdd7f71964965c14da460e3e7c1ea4c38762786f79db extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en-US\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <script src=\"/templates/js/jquery.min.js\" type=\"text/javascript\"></script>
        <link href=\"/templates/css/bootstrap.min.css\" rel=\"stylesheet\">
        <link href=\"/templates/css/uba.css\" rel=\"stylesheet\">
        <script>
            var navAdjust = function(){
                var scrollTop = \$(document).scrollTop();
                var headerHeight = \$('body>header').height();
                var b = headerHeight - scrollTop;
                if (b > 0) {
                    \$('.main-nav').css('margin-top', b);
                } else {
                    \$('.main-nav').css('margin-top', 0);
                }
            };
            \$(document).ready(function(){
                navAdjust();
                \$('.sub-nav .flow').click(function(){
                    \$('.flow-nav').toggleClass('on');
                });
            });
            \$(window).on('scroll',navAdjust);
            
        </script>
    </head>   
    <body>
        <header>
            <div class=\"wrapper\">
                <a href=\"/\"><img style=\"margin-left: 30px;\" src=\"/templates/img/logo.png\" /></a>
                <span style=\"font-size: 20px;\">demo展示页面</span>
            </div>
        </header>  
        <div class=\"wrapper clearfix\">
            <div id=\"navigation\">
                <nav class=\"main-nav\">
                    <ul class=\"sub-nav\">
                        <li><a href=\"/adminHome\">首页</a></li>
                        <li><a href=\"/adminLab\">link1</a></li>
                        <li><a href=\"/userInfo\">link2</a></li>
                        <li><a href=\"/adminAll\">link3</a></li>
                    </ul>
                </nav>
            </div>
            <div id=\"container\">
                ";
        // line 50
        $this->displayBlock('content', $context, $blocks);
        // line 52
        echo "            </div> 
        </div>       
        <footer>
            <div class=\"wrapper\">
                <div id=\"gotop\" style=\"display:none;\">返回顶部</div>
                @2015 v1.0.0707
            </div>
        </footer>
    </body>    
</html>

";
    }

    // line 6
    public function block_title($context, array $blocks = array())
    {
    }

    // line 50
    public function block_content($context, array $blocks = array())
    {
        // line 51
        echo "                ";
    }

    public function getTemplateName()
    {
        return "main.html";
    }

    public function getDebugInfo()
    {
        return array (  100 => 51,  97 => 50,  92 => 6,  77 => 52,  75 => 50,  28 => 6,  21 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "main.html", "/Users/wangyong/www/vino/templates/main.html");
    }
}
