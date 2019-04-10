<?php
    $fileName = FILENAME_RDF;
    $properties = PROPERTIES;
    $ontology = ONTOLOGY;
    $comment = COMMENT;
    $value = VALUE;

    $cidoc = new EasyRdf_graph();
    $cidoc->parseFile(__DIR__ . "/" . $fileName);
    $cidoc_resources = $cidoc->resources(); 

    $properties_array = Array();

    foreach ($cidoc_resources as $key => $val) {
        if ($val->type() == $properties){
            $property = str_replace('_', ' ', $val->localName());
            
            array_push($properties_array, Array
            (
                "o:label" => $property,
                "o:comment" => $val->get($comment)->getValue($value),
                "o:local_name" => strtolower($property),
                "o:vocabulary" => $ontology,
                "term" => $ontology . ":". strtolower($property),
            ));
        }
    }
?>