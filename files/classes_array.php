<?php
    /* Desenvolver as classes do cidoc mais tarde  */

    include_once 'requires.php';
    //require 'vendor/autoload.php';

    $fileName = FILENAME_RDF;
    $classes = CLASSES;
    $ontology = "istcidoc";

    $cidoc = createRDFGraph();
    $cidoc->parseFile(__DIR__ . "/" . $fileName);
    $cidoc_resources = $cidoc->resources(); 
    //print_r($cidoc_resources->all());  

    $class_array = Array();  
    $i = 0;

    foreach ($cidoc_resources as $key => $val) {
        if ($val->type() == $class){

            $class = str_replace('_', ' ', $val->localName());
            $class_array[$i] = Array
            (
                "o:label" => $class,
                "o:comment" => $class,
                "o:local_name" => $class,
                "o:vocabulary" => $ontology,
                "term" => $ontology . ":". $class,
            );
        }

        $i += 1;
    }

    print_r($class_array); exit();
?>