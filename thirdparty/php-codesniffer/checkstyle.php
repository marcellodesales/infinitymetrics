<?php
/**
 * $Id: checkstyle.php 202 2008-11-30 19:24:44Z Marcello Sales
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */

/*
 * This code applies a given xsl into a given xml and saves the transformation
 * into a given file path.
 *
 * It was first designed for the CodeSniffer, in order to generate the reports
 * during development and for the Subversion hook scripts.
 */

if ($argc < 3) {
    die("\n\nInfinity Metrics Codesytle Error: Wrong number of arguments... Please execute:
         \n\n'checkstyle.php transform.xsl doc.xml dest_path'\n\n");
}

echo "\nUsing the Stylesheet: " . $argv[1];
echo "\nUsing the Document: " . $argv[2];
echo "\nUsing Destination: " . $argv[3] . "\n\n";

// Allocate a new XSLT processor
$xp = new XsltProcessor();

// create a DOM document and load the XSL stylesheet
$xsl = new DomDocument;
$xsl->load($argv[1]);

// import the XSL styelsheet into the XSLT process
$xp->importStylesheet($xsl);

// create a DOM document and load the XML datat
$xml_doc = new DomDocument;
$xml_doc->load($argv[2]);

// transform the XML into HTML using the XSL file
if ($html = $xp->transformToXML($xml_doc)) {

    $handle = fopen ($argv[3], "w+");
    // now write
    fwrite($handle, $html);
    // and be a nice and close:
    fclose($handle);

    echo "\n\nCode style report successfully created at " . $argv[3]."\n\n";

} else {
    trigger_error('XSL transformation failed.', E_USER_ERROR);
} // if
?> 