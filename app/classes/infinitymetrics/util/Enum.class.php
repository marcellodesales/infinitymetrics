<?php
/**
 * $Id: Enum.class.php 202 2008-11-10 21:31:40Z marcellosales $
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

/**
 * The Enum defines a way to create a simulator for constants. Just extend this
 * class and pass the desired values in the contructor.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 10, 2008 2:59 PST
 * @version $Id$
 */
class Enum {
    
    /**
     * Values for the enum
     */
    protected $__this = array();

    /**
     * Constructs a new Enum with the parameters to be passed, but not read.
     */
    protected function __construct() {
        $args = func_get_args();
        $i = 0;
        do {
          $this->__this[$args[$i]] = $i;
        } while (count($args) > ++$i);
    }

    /**
     * @return The enumaration value based on the class call.
     * @throws Exception an instance of Exception in case the attribute doesn't exist.
     */
    public final function __get($n){
        if ($this->__this[$n] != -1) {
            return $n;
        } else throw new Exception("Illegal Enum Value exception");
    }

    /**
     * Setting is not permitted.
     * @param string member name
     * @param valeu the new value
     * @throws Exception if this method is called.
     */
    private function __set($member, $value) {
        throw new Exception('You cannot set a constant.');
    }

    /**
     * @return The collection of all values from the Enum
     */
    public function values() {
        return array_keys($this->__this);
    }
}
?>
