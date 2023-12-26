<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/rhythm_shortcodes/templates/rhythm-cms-menu.html.twig */
class __TwigTemplate_79ab018eca5cb1e08c9a6b826049f48c extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!-- Navigation panel -->
<nav class=\"main-nav ";
        // line 2
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["color"] ?? null), 2, $this->source), "html", null, true);
        echo "\">
  <div class=\"full-wrapper relative clearfix\">
    <!-- Logo ( * your text or image into link tag *) -->
    <div class=\"nav-logo-wrap local-scroll\">
      <a href=\"";
        // line 6
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("<front>"));
        echo "\" class=\"logo\">
          <img src=\"";
        // line 7
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["logo"] ?? null), 7, $this->source), "html", null, true);
        echo "\" alt=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_name"] ?? null), 7, $this->source), "html", null, true);
        echo "\" title = \"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_name"] ?? null), 7, $this->source), "html", null, true);
        echo "\" />
      </a>
    </div>
    <div class=\"mobile-nav\">
        <i class=\"fa fa-bars\"></i>
    </div>
    
    <!-- Main Menu -->
    <div class=\"inner-nav desktop-nav\">
      <ul class=\"clearlist\">
        ";
        // line 17
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["menu"] ?? null), 17, $this->source), "html", null, true);
        echo "
        <li><a style=\"height: 75px; line-height: 75px;\">&nbsp;</a></li>
        ";
        // line 19
        if (($context["search"] ?? null)) {
            // line 20
            echo "          <li class=\"search-dropdown-list mega-align-right\">
            <a href=\"#\" class=\"mn-has-sub\" style=\"height: 75px; line-height: 75px;\">
              <i class=\"fa fa-search\"></i> ";
            // line 22
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Search"));
            echo "
            </a>
            <ul class=\"mn-sub\" style=\"display: none;\">
              <li>
                <div class=\"mn-wrap\">
                  ";
            // line 27
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["search"] ?? null), 27, $this->source), "html", null, true);
            echo "
                </div>
              </li>
            </ul>
          </li>
        ";
        }
        // line 33
        echo "        ";
        if (($context["cart"] ?? null)) {
            // line 34
            echo "          <li>
            <a href=\"";
            // line 35
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("commerce_cart.page"));
            echo "\" style=\"height: 75px; line-height: 75px;\">
              <i class=\"fa fa-shopping-cart\"></i> ";
            // line 36
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Cart"));
            echo " (";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["cart_count"] ?? null), 36, $this->source), "html", null, true);
            echo ")
            </a>
          </li>
        ";
        }
        // line 40
        echo "        ";
        if (($context["language"] ?? null)) {
            // line 41
            echo "          <li>
            <a href=\"#\" style=\"height: 75px; line-height: 75px;\" class=\"mn-has-sub\">";
            // line 42
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["lang_code"] ?? null), 42, $this->source), "html", null, true);
            echo " <i class=\"fa fa-angle-down\"></i></a>
            ";
            // line 43
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["language"] ?? null), 43, $this->source), "html", null, true);
            echo "
          </li>
        ";
        }
        // line 46
        echo "      </ul>
    </div>
    <!-- End Main Menu -->
  </div>
</nav>";
    }

    public function getTemplateName()
    {
        return "modules/custom/rhythm_shortcodes/templates/rhythm-cms-menu.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 46,  127 => 43,  123 => 42,  120 => 41,  117 => 40,  108 => 36,  104 => 35,  101 => 34,  98 => 33,  89 => 27,  81 => 22,  77 => 20,  75 => 19,  70 => 17,  53 => 7,  49 => 6,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!-- Navigation panel -->
<nav class=\"main-nav {{ color }}\">
  <div class=\"full-wrapper relative clearfix\">
    <!-- Logo ( * your text or image into link tag *) -->
    <div class=\"nav-logo-wrap local-scroll\">
      <a href=\"{{ url('<front>') }}\" class=\"logo\">
          <img src=\"{{ logo }}\" alt=\"{{ site_name }}\" title = \"{{ site_name }}\" />
      </a>
    </div>
    <div class=\"mobile-nav\">
        <i class=\"fa fa-bars\"></i>
    </div>
    
    <!-- Main Menu -->
    <div class=\"inner-nav desktop-nav\">
      <ul class=\"clearlist\">
        {{ menu }}
        <li><a style=\"height: 75px; line-height: 75px;\">&nbsp;</a></li>
        {% if search %}
          <li class=\"search-dropdown-list mega-align-right\">
            <a href=\"#\" class=\"mn-has-sub\" style=\"height: 75px; line-height: 75px;\">
              <i class=\"fa fa-search\"></i> {{ 'Search'|t }}
            </a>
            <ul class=\"mn-sub\" style=\"display: none;\">
              <li>
                <div class=\"mn-wrap\">
                  {{ search }}
                </div>
              </li>
            </ul>
          </li>
        {% endif %}
        {% if cart %}
          <li>
            <a href=\"{{ url('commerce_cart.page') }}\" style=\"height: 75px; line-height: 75px;\">
              <i class=\"fa fa-shopping-cart\"></i> {{ 'Cart'|t }} ({{ cart_count }})
            </a>
          </li>
        {% endif %}
        {% if language %}
          <li>
            <a href=\"#\" style=\"height: 75px; line-height: 75px;\" class=\"mn-has-sub\">{{ lang_code }} <i class=\"fa fa-angle-down\"></i></a>
            {{ language }}
          </li>
        {% endif %}
      </ul>
    </div>
    <!-- End Main Menu -->
  </div>
</nav>", "modules/custom/rhythm_shortcodes/templates/rhythm-cms-menu.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/modules/custom/rhythm_shortcodes/templates/rhythm-cms-menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 19);
        static $filters = array("escape" => 2, "t" => 22);
        static $functions = array("url" => 6);

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 't'],
                ['url']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
