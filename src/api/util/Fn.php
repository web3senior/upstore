<?php

/**
 * @param string $buffer
 * @return string
 */
function bufferFilter($buffer)
{
  $buffer = str_replace('...', '…', $buffer);
  $buffer = str_replace('> <', '><', $buffer);
  $buffer = trim(preg_replace('/\s+/', ' ', $buffer));
  return $buffer;
}