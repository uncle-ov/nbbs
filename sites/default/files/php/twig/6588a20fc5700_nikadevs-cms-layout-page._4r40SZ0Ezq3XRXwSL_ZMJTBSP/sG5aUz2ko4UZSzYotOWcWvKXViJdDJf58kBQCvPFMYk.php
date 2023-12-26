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

/* modules/custom/nikadevs_cms/templates/nikadevs-cms-layout-page.html.twig */
class __TwigTemplate_3d170061fe46016dd7b5e84efae1f55e extends Template
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
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["layout"] ?? null), "rows", [], "any", false, false, true, 1));
        foreach ($context['_seq'] as $context["id"] => $context["row"]) {
            // line 2
            echo "
    ";
            // line 3
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "settings", [], "any", false, true, true, 3), "tag", [], "any", true, true, true, 3)) {
                // line 4
                echo "      ";
                if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "settings", [], "any", false, false, true, 4), "tag", [], "any", false, false, true, 4) == "none")) {
                    // line 5
                    echo "          ";
                    $context["tag"] = "";
                    // line 6
                    echo "      ";
                } else {
                    // line 7
                    echo "          ";
                    $context["tag"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "settings", [], "any", false, false, true, 7), "tag", [], "any", false, false, true, 7);
                    // line 8
                    echo "      ";
                }
                // line 9
                echo "    ";
            } else {
                // line 10
                echo "        ";
                $context["tag"] = "";
                // line 11
                echo "    ";
            }
            // line 12
            echo "
    ";
            // line 13
            if (($context["tag"] ?? null)) {
                // line 14
                echo "        <";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["tag"] ?? null), 14, $this->source), "html", null, true);
                echo " class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_join_filter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "wrap", [], "any", false, false, true, 14), "attributes", [], "any", false, false, true, 14), "class", [], "any", false, false, true, 14), 14, $this->source), " "), "html", null, true);
                echo "\" style=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "wrap", [], "any", false, false, true, 14), "attributes", [], "any", false, false, true, 14), "style", [], "any", false, false, true, 14), 14, $this->source), "html", null, true);
                echo "\">
    ";
            }
            // line 16
            echo "
    ";
            // line 17
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "settings", [], "any", false, true, true, 17), "full_width", [], "any", true, true, true, 17)) {
                // line 18
                echo "        ";
                $context["container_class"] = "-fluid";
                // line 19
                echo "    ";
            } else {
                // line 20
                echo "        ";
                $context["container_class"] = "";
                // line 21
                echo "    ";
            }
            // line 22
            echo "
      <div class=\"container";
            // line 23
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container_class"] ?? null), 23, $this->source), "html", null, true);
            echo "\">

        <div id=\"";
            // line 25
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 25), "id", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
            echo "\" class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_join_filter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 25), "class", [], "any", false, false, true, 25), 25, $this->source), " "), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_join_filter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "settings", [], "any", false, false, true, 25), "class", [], "any", false, false, true, 25), 25, $this->source), " "), "html", null, true);
            echo "\">

          ";
            // line 27
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["layout"] ?? null), "regions", [], "any", false, false, true, 27));
            foreach ($context['_seq'] as $context["region_key"] => $context["region"]) {
                // line 28
                echo "
            ";
                // line 29
                if ((($context["id"] == twig_get_attribute($this->env, $this->source, $context["region"], "row_id", [], "any", false, false, true, 29)) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["region"], "content", [], "any", false, false, true, 29)))) {
                    // line 30
                    echo "
              ";
                    // line 31
                    if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "settings", [], "any", false, false, true, 31), "tag", [], "any", false, false, true, 31)) {
                        // line 32
                        echo "                <";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "settings", [], "any", false, false, true, 32), "tag", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                        echo " id=\"";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "attributes", [], "any", false, false, true, 32), "id", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                        echo "\" class=\"";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_join_filter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "attributes", [], "any", false, false, true, 32), "class", [], "any", false, false, true, 32), 32, $this->source), " "), "html", null, true);
                        echo "\" style=\"";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "attributes", [], "any", false, false, true, 32), "style", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                        echo "\">
              ";
                    }
                    // line 34
                    echo "
                ";
                    // line 35
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["region"], "content", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
                    echo "

              ";
                    // line 37
                    if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "settings", [], "any", false, false, true, 37), "tag", [], "any", false, false, true, 37)) {
                        // line 38
                        echo "                </";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["region"], "settings", [], "any", false, false, true, 38), "tag", [], "any", false, false, true, 38), 38, $this->source), "html", null, true);
                        echo ">
              ";
                    }
                    // line 40
                    echo "
            ";
                }
                // line 42
                echo "
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['region_key'], $context['region'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 44
            echo "
        </div>

      </div>

    ";
            // line 49
            if (($context["tag"] ?? null)) {
                // line 50
                echo "        </";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["tag"] ?? null), 50, $this->source), "html", null, true);
                echo ">
    ";
            }
            // line 52
            echo "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['id'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "modules/custom/nikadevs_cms/templates/nikadevs-cms-layout-page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  189 => 52,  183 => 50,  181 => 49,  174 => 44,  167 => 42,  163 => 40,  157 => 38,  155 => 37,  150 => 35,  147 => 34,  135 => 32,  133 => 31,  130 => 30,  128 => 29,  125 => 28,  121 => 27,  112 => 25,  107 => 23,  104 => 22,  101 => 21,  98 => 20,  95 => 19,  92 => 18,  90 => 17,  87 => 16,  77 => 14,  75 => 13,  72 => 12,  69 => 11,  66 => 10,  63 => 9,  60 => 8,  57 => 7,  54 => 6,  51 => 5,  48 => 4,  46 => 3,  43 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% for id, row in layout.rows %}

    {% if row.settings.tag is defined %}
      {% if row.settings.tag == 'none' %}
          {% set tag = '' %}
      {% else %}
          {% set tag = row.settings.tag %}
      {% endif %}
    {% else %}
        {% set tag = '' %}
    {% endif %}

    {% if tag %}
        <{{ tag }} class=\"{{ row.wrap.attributes.class|join(' ') }}\" style=\"{{ row.wrap.attributes.style }}\">
    {% endif %}

    {% if row.settings.full_width is defined %}
        {% set container_class = '-fluid' %}
    {% else %}
        {% set container_class = '' %}
    {% endif %}

      <div class=\"container{{ container_class }}\">

        <div id=\"{{ row.attributes.id }}\" class=\"{{ row.attributes.class|join(' ') }} {{ row.settings.class|join(' ') }}\">

          {% for region_key, region in layout.regions %}

            {% if id == region.row_id and region.content is not empty %}

              {% if region.settings.tag %}
                <{{ region.settings.tag }} id=\"{{ region.attributes.id }}\" class=\"{{ region.attributes.class|join(' ') }}\" style=\"{{ region.attributes.style }}\">
              {% endif %}

                {{ region.content }}

              {% if region.settings.tag %}
                </{{ region.settings.tag }}>
              {% endif %}

            {% endif %}

          {% endfor %}

        </div>

      </div>

    {% if tag %}
        </{{ tag }}>
    {% endif %}

{% endfor %}
", "modules/custom/nikadevs_cms/templates/nikadevs-cms-layout-page.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/modules/custom/nikadevs_cms/templates/nikadevs-cms-layout-page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 1, "if" => 3, "set" => 5);
        static $filters = array("escape" => 14, "join" => 14);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if', 'set'],
                ['escape', 'join'],
                []
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
