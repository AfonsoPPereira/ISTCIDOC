var properties_array = new Array({},{},{},{});

properties_array[0]['property_term'] = 'istcidoc:identifier';
properties_array[0]['property_label'] = 'Identifier';
properties_array[0]['property_description'] = 'item identifier';
properties_array[0]['literal'] = true;
properties_array[0]['resource'] = false;
properties_array[0]['uri'] = false;
properties_array[0]['default_term'] = 'literal';

properties_array[1]['property_term'] = 'istcidoc:title';
properties_array[1]['property_label'] = 'Title/Name';
properties_array[1]['property_description'] = 'item name/title';
properties_array[1]['literal'] = true;
properties_array[1]['resource'] = false;
properties_array[1]['uri'] = false;
properties_array[1]['default_term'] = 'literal';

properties_array[2]['property_term'] = 'istcidoc:description';
properties_array[2]['property_label'] = 'Description';
properties_array[2]['property_description'] = 'description of the item';
properties_array[2]['literal'] = true;
properties_array[2]['resource'] = false;
properties_array[2]['uri'] = false;
properties_array[2]['default_term'] = 'literal';

properties_array[3]['property_term'] = 'istcidoc:location';
properties_array[3]['property_label'] = 'Location';
properties_array[3]['property_description'] = 'location of the item';
properties_array[3]['literal'] = false;
properties_array[3]['resource'] = true;
properties_array[3]['uri'] = false;
properties_array[3]['default_term'] = 'resource_cidoc';
