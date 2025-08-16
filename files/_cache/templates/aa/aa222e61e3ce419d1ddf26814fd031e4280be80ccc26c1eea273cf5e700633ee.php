<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* components/form/link_existing_or_new.html.twig */
class __TwigTemplate_cbb86c1297ccd75d5539f56f6e979e4f6547469f02f15d2b61b15db280c2ad7c extends Template
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
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        yield "
";
        // line 34
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "components/form/link_existing_or_new.html.twig", 34)->unwrap();
        // line 35
        yield "
";
        // line 36
        $context["rand"] = ((array_key_exists("rand", $context)) ? (Twig\Extension\CoreExtension::default(($context["rand"] ?? null), Twig\Extension\CoreExtension::random($this->env->getCharset()))) : (Twig\Extension\CoreExtension::random($this->env->getCharset())));
        // line 37
        yield "<div class=\"firstbloc\">
   <form id=\"";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((Twig\Extension\CoreExtension::lower($this->env->getCharset(), ($context["link_itemtype"] ?? null)) . "_form") . ($context["rand"] ?? null)), "html", null, true);
        yield "\" name=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((Twig\Extension\CoreExtension::lower($this->env->getCharset(), ($context["link_itemtype"] ?? null)) . "_form") . ($context["rand"] ?? null)), "html", null, true);
        yield "\" method=\"post\" action=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeFormPath(($context["link_itemtype"] ?? null)), "html", null, true);
        yield "\">
      ";
        // line 39
        yield CoreExtension::callMacro($macros["fields"], "macro_largeTitle", [Twig\Extension\CoreExtension::sprintf(__("Add a %s"), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName(($context["target_itemtype"] ?? null)))], 39, $context, $this->getSourceContext());
        yield "
      ";
        // line 40
        yield CoreExtension::callMacro($macros["fields"], "macro_hiddenField", [$this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeForeignKey(($context["source_itemtype"] ?? null)), ($context["source_items_id"] ?? null), "", ["no_label" => true]], 40, $context, $this->getSourceContext());
        // line 42
        yield "
      ";
        // line 43
        $context["primary_dropdown_itemtype"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["dropdown_options"] ?? null), "itemtype", [], "array", true, true, false, 43)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_0 = ($context["dropdown_options"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["itemtype"] ?? null) : null), ($context["target_itemtype"] ?? null))) : (($context["target_itemtype"] ?? null)));
        // line 44
        yield "      <div class=\"d-flex\">
         <div class=\"col-auto\">
            ";
        // line 46
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [($context["primary_dropdown_itemtype"] ?? null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeForeignKey(($context["primary_dropdown_itemtype"] ?? null)), "", "", Twig\Extension\CoreExtension::merge(["no_label" => true, "field_class" => "", "rand" =>         // line 49
($context["rand"] ?? null)],         // line 50
($context["dropdown_options"] ?? null))], 46, $context, $this->getSourceContext());
        yield "
            ";
        // line 51
        if (array_key_exists("ajax_dropdown", $context)) {
            // line 52
            yield "               ";
            $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Ajax::updateItemOnSelectEvent", [(($__internal_compile_1 =             // line 53
($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["toobserve"] ?? null) : null), (($__internal_compile_2 = (($__internal_compile_3 =             // line 54
($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["toupdate"] ?? null) : null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["id"] ?? null) : null), (($__internal_compile_4 =             // line 55
($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["url"] ?? null) : null), (($__internal_compile_5 =             // line 56
($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["params"] ?? null) : null)]);
            // line 58
            yield "            ";
        }
        // line 59
        yield "            ";
        if (array_key_exists("ajax_dropdown", $context)) {
            // line 60
            yield "               <span id=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_6 = (($__internal_compile_7 = ($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["toupdate"] ?? null) : null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["id"] ?? null) : null), "html", null, true);
            yield "\">
                  ";
            // line 61
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [(($__internal_compile_8 = (($__internal_compile_9 = ($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["toupdate"] ?? null) : null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["itemtype"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeForeignKey((($__internal_compile_10 = (($__internal_compile_11 = ($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["toupdate"] ?? null) : null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["itemtype"] ?? null) : null)), "", "", Twig\Extension\CoreExtension::merge(["no_label" => true, "field_class" => "", "rand" =>             // line 64
($context["rand"] ?? null)], (($__internal_compile_12 = (($__internal_compile_13 =             // line 65
($context["ajax_dropdown"] ?? null)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["toupdate"] ?? null) : null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["params"] ?? null) : null))], 61, $context, $this->getSourceContext());
            yield "
               </span>
            ";
        }
        // line 68
        yield "         </div>
         <div class=\"col-auto\">
            <button class=\"btn btn-primary ms-1\" type=\"submit\" name=\"add\">
               <i class=\"ti ti-link\"></i>
               <span>";
        // line 72
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Add"), "html", null, true);
        yield "</span>
            </button>
         </div>
         <div class=\"col-auto ms-4\">
            ";
        // line 76
        if (($context["create_link"] ?? null)) {
            // line 77
            yield "               ";
            $context["target_form_path"] = (((($this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeFormPath(($context["target_itemtype"] ?? null)) . "?_") . $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeForeignKey(($context["source_itemtype"] ?? null))) . "=") . ($context["source_items_id"] ?? null));
            // line 78
            yield "               ";
            $context["create_url"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["create_link"] ?? null), "url", [], "array", true, true, false, 78)) ? ((($__internal_compile_14 = ($context["create_link"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["url"] ?? null) : null)) : (($context["target_form_path"] ?? null)));
            // line 79
            yield "               <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["create_url"] ?? null), "html", null, true);
            yield "\" class=\"btn btn-primary\">
                  <i class=\"ti ti-plus\"></i>
                  <span>";
            // line 81
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(__("Create a %1\$s from this %2\$s"), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName(($context["target_itemtype"] ?? null)), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName(($context["source_itemtype"] ?? null))), "html", null, true);
            yield "</span>
               </a>
            ";
        }
        // line 84
        yield "         </div>
      </div>
      ";
        // line 86
        yield CoreExtension::callMacro($macros["fields"], "macro_hiddenField", ["_glpi_csrf_token", Session::getNewCSRFToken()], 86, $context, $this->getSourceContext());
        yield "
   </form>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/link_existing_or_new.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  145 => 86,  141 => 84,  135 => 81,  129 => 79,  126 => 78,  123 => 77,  121 => 76,  114 => 72,  108 => 68,  102 => 65,  101 => 64,  100 => 61,  95 => 60,  92 => 59,  89 => 58,  87 => 56,  86 => 55,  85 => 54,  84 => 53,  82 => 52,  80 => 51,  76 => 50,  75 => 49,  74 => 46,  70 => 44,  68 => 43,  65 => 42,  63 => 40,  59 => 39,  51 => 38,  48 => 37,  46 => 36,  43 => 35,  41 => 34,  38 => 33,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/link_existing_or_new.html.twig", "C:\\xampp\\htdocs\\glpi\\templates\\components\\form\\link_existing_or_new.html.twig");
    }
}
