<?php
/**
 * $Id: WorkspaceState.class.php 202 2008-11-10 00:12:40Z marcellosales $
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
require_once 'infinitymetrics/util/Enum.class.php';
/**
 * The WorkspaceState is the implementation of the Enum for the possible states
 * of the Metrics Workspace:
 *
 * <li>NEW: For the brand new metrics worksapce</li>
 * <li>ACTIVE: When the project owner decides activate</li>
 * <li>PAUSED: When the instructor decides to pause the workspace. Maybe during
 * holidays, vacations, or some event that the metrics collection should not be
 * active</li>
 * <li>INACTIVE: The last stage of the metrics workspace life-cycle</li>
 *
 * Since the implementation of Enums are singletons, the instance of this class
 * must be acquired by the getInstance() method.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 10, 2008 2:59 PST
 * @version $Id$
 */
class WorkspaceState extends Enum {

    private static $singleton;

    /**
     * Creates a new instance of the WorkspaceState, as described in the class
     * description.
     */
    public function __construct() {
        parent::__construct("NEW", "ACTIVE", "PAUSED", "INACTIVE");
    }

    /**
     * @return WorkspaceState the single instance of this enum.
     */
    public static function getInstance() {
         if (self::$singleton == null) {
             self::$singleton = new WorkspaceState();
         }
         return self::$singleton;
    }
}

?>
