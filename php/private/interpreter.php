<?php

class Token {
  public $type;
  public $content;
  public $parameters;

  function __construct($type, $content = "", $parameters = []) {
    $this->type = $type;
    $this->content = $content;
    $this->parameters = $parameters;
  }

  function addToContent($strToAdd) {
    $this->content .= $strToAdd;
  }
}

function interpret($input) {
  return parser(lexer($input));
}

function lexer($text) {
  $currentToken;
  $allTokens = [];
  $charEscaped = false;

  foreach (str_split($text) as $char) {
    if ($charEscaped) {
      $currentToken->addToContent($char);
      $charEscaped = false;
      continue;
    }
    switch($char) {
      case " ":
        if ($currentToken->type == "Tag") {
          newToken("Text", $currentToken, $allTokens);
        }
        else if (str_starts_with($currentToken->type, "Tag") || str_ends_with($currentToken->type, "Open") || str_ends_with($currentToken->type, "Close") || $currentToken->type == "Pipe") {
          newToken("Text", $currentToken, $allTokens);
          $currentToken->addToContent($char);
        }
        else {
          $currentToken->addToContent($char);
        }
        break;
      case "\n":
        newToken("NewLine", $currentToken, $allTokens);
        break;
      case "&":
        newToken("Tag", $currentToken, $allTokens);
        break;
      case "\\":
        $charEscaped = true;
        break;
      case "*":
        newToken("TagAsterisk", $currentToken, $allTokens);
        break;
      case "_":
        newToken("TagUnderscore", $currentToken, $allTokens);
        break;
      case "~":
        newToken("TagTilde", $currentToken, $allTokens);
        break;
      case "^":
        newToken("TagCaret", $currentToken, $allTokens);
        break;
      case "|":
        newToken("Pipe", $currentToken, $allTokens);
        break;
      case "[":
        newToken("BracketOpen", $currentToken, $allTokens);
        break;
      case "]":
        newToken("BracketClose", $currentToken, $allTokens);
        break;
      default:
        if (!isset($currentToken) || in_array($currentToken->type, ["NewLine", "TagAsterisk", "TagUnderscore", "TagTilde", "TagCaret", "Pipe", "BracketOpen", "BracketClose"])) {
          newToken("Text", $currentToken, $allTokens);
        }
        $currentToken->addToContent($char);
    }
  }
  $allTokens[] = $currentToken;
  return $allTokens;
}

function newToken($type, &$ct, &$at) {
  if (isset($ct)) {
    $at[] = $ct;
  }

  $ct = new Token($type);
}

function parser($tokenArray) {
  $tokenIndex = 0;

  return evaluateInTag("BODY", null, $tokenArray, $tokenIndex);
}

function evaluateInTag($type, $returnOn, &$ta, &$ti, $content = [], $parameterArray = []) {
  $out = [
    "type" => $type,
    "parameters" => $parameterArray,
    "content" => $content
  ];

  while ($ti < count($ta)) {
    $token = $ta[$ti];
    if ((isset($returnOn->type) ? $returnOn->type : "") == "Tag" && (isset($returnOn->content) ? $returnOn->content : "") == "table") {
      $ti++;
      $outIndex = isset($outIndex) ? $outIndex: 0;
      $outIndexIndex = isset($outIndexIndex) ? $outIndexIndex: 0;
      
      if ($token->type == "Tag" && $token->content == "table") {
        break;
      }
      else if ($token->type == "Pipe") {
        $outIndexIndex += 1;
        $out["content"][$outIndex][] = [];
      }
      else if ($token->type == "NewLine") {
        $outIndex += 1;
        $outIndexIndex = 0;
        $out["content"][] = [[]];
      }
      else {
        $out["content"][$outIndex][$outIndexIndex][] = $token;
        var_dump($out);
      }
      continue;
    }

    if ($returnOn == "DOUBLENL") {
      if ($token->type != "Text" && $ta[$ti + 1]->type != "Text") {
        break;
      }
    }

    $ti++;

    if ($token == $returnOn) {
      break;
    }

    if ($token->type == "Text") {
      if ($ta[$ti - 2]->type == "NewLine") {
        if ($ta[$ti - 3]->type == "Text" && $ta[$ti - 4]->type != "Tag") {
          $out["content"][count($out["content"]) - 1] .= "\n".$token->content;
        }
        else {
          $out["content"][] = evaluateInTag("p", "DOUBLENL", $ta, $ti, [$token->content]);
        }
      }
      else if (!isset($ta[$ti - 2])) {
        $out["content"][] = evaluateInTag("p", "DOUBLENL", $ta, $ti, [$token->content]);
      }
      else {
        $out["content"][] = $token->content;
      }
    }
    else if ($token->type == "Tag") {
      $tagType = $token->content;

      switch ($tagType) {
        case "#":
          $outType = "h2";
          $returnToken = new Token("NewLine");
          break;
        case "##":
          $outType = "h3";
          $returnToken = new Token("NewLine");
          break;
        case "###":
          $outType = "h4";
          $returnToken = new Token("NewLine");
          break;
        case "####":
          $outType = "h5";
          $returnToken = new Token("NewLine");
          break;
        case "quote":
          $outType = "blockquote";
          $returnToken = new Token("Tag", "quote");
          break;
<<<<<<< HEAD
        case "table":
          $outType = "table";
          $returnToken = new Token("Tag", "table");
=======
        case "personsays":
          $outType = "personsays";
          $returnToken = new Token("personsays");
          break;
        case "image":
          $outType = "img";
          $returnToken = new Token("NewLine");
>>>>>>> 596729110e6002a656730b79535340d94c9e323b
          break;
        default:
          echo "Error: Unrecognized tag type '".$token->content."'\n";
          exit();
      }
      
      if ($returnToken->type == "Tag" && $ta[$ti]->type == "Text" && $ta[$ti + 1]->type == "NewLine") {
        $ti += 2;
        $result = evaluateInTag($outType, $returnToken, $ta, $ti, [], explode(" ", $ta[$ti - 2]->content));
      }
      else {
        $result = evaluateInTag($outType, $returnToken, $ta, $ti);
      }
      
      $out["content"][] = $result;
    }
    else if (str_starts_with($token->type, "Tag")) {
      $out["content"][] = evaluateInTag([
        "Asterisk" => "i",
        "Underscore" => "u",
        "Tilde" => "s",
        "Caret" => "b"
      ][substr($token->type, 3)], new Token($token->type), $ta, $ti);
    }
    else if ($token->type == "BracketOpen") {
      if ($ta[$ti]->type == "Text" && $ta[$ti + 1]->type == "Pipe" && $ta[$ti + 2]->type == "Text" && $ta[$ti + 3]->type == "BracketClose") {
        $out["content"][] = [
          "type" => "a",
          "parameters" => [trim($ta[$ti + 2]->content)],
          "content" => [trim($ta[$ti]->content)]
        ];
        $ti +=4;
      }
      else {
        echo "Error: Unexpected token '".$token->content." (token #".$ti.")'\n";
        exit();
      }
    }
    else if ($token->type == "NewLine") {}
    else {
      echo "Error: Invalid token '".$returnOn." (token #".$ti.")'\n";
      exit();
    }
  }

  return $out;
}

?>