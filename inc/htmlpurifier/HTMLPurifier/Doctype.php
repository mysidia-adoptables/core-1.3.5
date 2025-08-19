<?php

/**
 * Represents a document type, contains information on which modules
 * need to be loaded.
 * @note This class is inspected by Printer_HTMLDefinition->renderDoctype.
 *       If structure changes, please update that function.
 */
class HTMLPurifier_Doctype
{
    public function __construct(
        /**
         * Full name of doctype
         */
        public $name = null,
        /**
         * Is the language derived from XML (i.e. XHTML)?
         */
        public $xml = true,
        /**
         * List of standard modules (string identifiers or literal objects)
         * that this doctype uses
         */
        public $modules = [],
        /**
         * List of modules to use for tidying up code
         */
        public $tidyModules = [],
        /**
         * List of aliases for this doctype
         */
        public $aliases = [],
        /**
         * Public DTD identifier
         */
        public $dtdPublic = null,
        /**
         * System DTD identifier
         */
        public $dtdSystem = null
    ) {
    }
}

// vim: et sw=4 sts=4
