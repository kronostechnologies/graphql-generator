<?php


namespace GraphQLGen\Generator;


class StubFormatter {
    public function getDescriptionLine($description, $indentLevel = 4) {
        $indent = str_repeat("    ", $indentLevel);

        if (!is_null($description)) {
            $trimmedDescription = trim($description);
            $singleLineDescription = str_replace("\n", ",", $trimmedDescription);
            $descriptionSlashed = addslashes($singleLineDescription);

            return "\n{$indent}'description' => '{$descriptionSlashed}',";
        }
        else {
            return "";
        }
    }
}