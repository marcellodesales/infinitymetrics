<?php
/**
 * $Id: AbstractSubject.class.php 144 2008-09-24 02:59:40Z marcellosales $
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
 * Observer interface is part of the implementation of the Go4 Observer
 * Design-Pattern. More info Go4 Design-Pattern:
 * http://www.fluffycat.com/PHP-Design-Patterns/Builder/
 * Also can be referred as Listener.
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 * @package net.java.dev.ppm-8.util.go4.observer
 */
interface Observer {

    /**
     * Called by the subject to update the observer.
     * @param AbstractSubject $subjectIn
     * @abstract function just updates the Subject.
     */    
    public function update($subjectNewValue);
}
?>
