<?php
/**
 * Copyright © 2015, Ambroise Maupate
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @file TrueTypeConverter.php
 * @author Ambroise Maupate
 */
namespace WebfontGenerator\Converters;

use WebfontGenerator\Util\StringHandler;
use Symfony\Component\HttpFoundation\File\File;

class TrueTypeConverter implements ConverterInterface
{
    protected $fontforge = null;

    public function __construct($binPath)
    {
        $this->fontforge = $binPath;
    }

    public function convert(File $input)
    {
        $output = [];
        $outFile = $this->getTTFPath($input);
        exec(
            $this->fontforge.' -script '.ROOT.'/assets/scripts/tottf.pe "'.$input->getRealPath().'"',
            $output,
            $return
        );

        if (0 !== $return) {
            throw new \RuntimeException('Fontforge could not convert '.$input->getBasename().' to TrueType format.');
        } else {
            return new File($outFile);
        }
    }

    public function getTTFPath(File $input)
    {
        $basename = StringHandler::slugify($input->getBasename('.'.$input->getExtension()));

        return $input->getPath().'/'.$basename.'.ttf';
    }
}
