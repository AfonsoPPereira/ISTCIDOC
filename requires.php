<?php

//require 'vendor/autoload.php';

/* File Name define (RDF/OWL) */
define("FILENAME_RDF", "ist-crm.rdf");

/* Generic Defines */
define("TYPE", "rdf:type");
define("LABEL", "rdfs:label");
define("SCOPENOTE", "skos:scopeNote");
define("RANGE", "rdfs:range");
define("DOMAIN", "rdfs:domain");
define("ABOUT", "rdf:about");

/* Classes Define */
define("CLASSES", "owl:Class");
define("SUBCLASSOF", "rdfs:subClassOf");

/* Properties Define */
define("PROPERTIES", "owl:ObjectProperty");
define("SUBPROPERTYOF", "rdfs:subPropertyOf");

/* Ontology Name */
define("ONTOLOGY", "istcidoc");

if (!function_exists('createRDFGraph'))   {
	function createRDFGraph(){
		return new EasyRdf_graph();
	}
}

?>
