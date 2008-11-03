<?php
/**
 * $Id: ObservableSubject.class.php 144 2008-09-24 02:59:40Z marcellosales $
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

include_once('Observable.class.php');

/**
 * ObservableSubject is an abstract class part of the implementation of the 
 * Go4 Observer Design-Pattern. The online version is incorrect... Still, for m
 * ore info Go4 Design-Pattern http://www.fluffycat.com/PHP-Design-Patterns/Builder/
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
abstract class ObservableSubject implements Observable {

    private $observers;
    private $subjectValue;
    
    function __construct() {
        $this->observers = array();
    }

    function addObserver(Observer $observer_in) {
      //could also use array_push($this->observers, $observer_in);
      $this->observers[] = $observer_in;
    }

    function removeObserver(Observer $observer_in) {
      $key = array_search($this->observers, $observer_in);
      unset($this->observers[$okey]);
//      foreach($this->observers as $okey => $oval) {
//        if ($oval == $observer_in) {
//          unset($this->observers[$okey]);
//        }
//      }
    }

    function notifyObservers() {
      foreach($this->observers as $obsv) {
        $obsv->update($this->subjectValue);
      }
    }
    
    function hasObserver() {
        return sizeof($this->observers) > 0;
    }

    function updateSubject($newSubjectValue) {
      $this->subjectValue = $newSubjectValue;
      $this->notifyObservers();
    }
  }
?>