<?php

class Token {
  public $type;
  public $content;

  function __construct($type, $content = "") {
    $this->type = $type;
    $this->content = $content;
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
        else if (str_starts_with($currentToken->type, "Tag")) {
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
      default:
        if (!isset($currentToken) || in_array($currentToken->type, ["NewLine", "TagAsterisk", "TagUnderscore", "TagTilde"])) {
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

function evaluateInTag($type, $returnOn, &$ta, &$ti, $content = []) {
  $out = [
    "type" => $type,
    "content" => $content
  ];

  while ($ti < count($ta)) {
    $token = $ta[$ti];

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
        default:
          echo "Error: Unrecognized tag type '".$token->content."'\n";
          exit();
      }

      $out["content"][] = evaluateInTag($outType, $returnToken, $ta, $ti);
    }
    else if (str_starts_with($token->type, "Tag")) {
      $out["content"][] = evaluateInTag([
        "Asterisk" => "b",
        "Underscore" => "i",
        "Tilde" => "u"
      ][substr($token->type, 3)], new Token($token->type), $ta, $ti);
    }
    else if ($token->type == "NewLine") {}
    else {
      echo "Error: Invalid token '".$token->content." (token #".$ti.")'\n";
      exit();
    }
  }

  return $out;
}

?>