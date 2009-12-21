;;; simpletest.el --- Helper function for unittest with Simpletest

;; Copyright (C) 2009 Free Software Foundation, Inc.
;;
;; Author: Ye Wenbin <wenbinye@gmail.com>
;; Maintainer: Ye Wenbin <wenbinye@gmail.com>
;; Created: 21 Dec 2009
;; Version: 0.01
;; Keywords: languages, tools

;; This program is free software; you can redistribute it and/or modify
;; it under the terms of the GNU General Public License as published by
;; the Free Software Foundation; either version 2, or (at your option)
;; any later version.
;;
;; This program is distributed in the hope that it will be useful,
;; but WITHOUT ANY WARRANTY; without even the implied warranty of
;; MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
;; GNU General Public License for more details.
;;
;; You should have received a copy of the GNU General Public License
;; along with this program; if not, write to the Free Software
;; Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

;;; Commentary:
;; Features privodes:
;;  1. automatic generate test
;;  2. run test for select function

;; Put this file into your load-path and the following into your ~/.emacs:
;;   (require 'simpletest)

;;; Code:

(eval-when-compile
  (require 'cl))

(defvar simpletest-class-regexp
  "^\\s-*class\\s-+\\(\\(?:\\sw\\|\\s_\\)+\\)\\s-*"
  "Regexp that match class declaration")

(defvar simpletest-function-regexp
  "^\\s-*\\(\\(?:\\(?:abstract\\|final\\|private\\|protected\\|public\\|static\\)\\s-+\\)*\\)function\\s-+\\(\\(?:\\sw\\|\\s_\\)+\\)\\s-*("
  "Regexp that match function declaration")

(defvar simpletest-class-map-functions
  '(simpletest-find-test-class-suffix . simpletest-find-source-class-suffix)
  "Functions to convert between class and test class")

(defvar simpletest-find-file-functions
  '(simpletest-find-source-file-cached . simpletest-find-test-file-cached)
  "Functions to find source file for given class")

(defvar simpletest-class-cache-file ".simpletest-classes"
  "Cache file name for lookup class-file")

(defun simpletest-class-ap ()
  "Get current class name"
  (save-excursion
    (if (re-search-backward simpletest-class-regexp nil t)
        (match-string 1))))

(defun simpletest-function-ap ()
  "Get current function name"
  (save-excursion
    (goto-char (line-end-position))
    (if (re-search-backward simpletest-function-regexp nil t)
        (cons (match-string 1) (match-string 2)))))

(defun simpletest-find-test-class-suffix (class)
  "Add suffix 'Test' to get test class name"
  (concat class "Test"))

(defun simpletest-find-source-class-suffix (test-class)
  "Remove suffix 'Test' to get source class name"
  (replace-regexp-in-string "Test$" "" test-class))

(defun simpletest-find-test-class (class)
  "Get test class name for `class'"
  (funcall (car simpletest-class-map-functions) class))

(defun simpletest-find-source-class (test-class)
  "Get source class name for `test-class'"
  (funcall (cdr simpletest-class-map-functions) test-class))

(defun simpletest-find-top-directory (file &optional dir)
  "Find `file' in all parent directories of `dir'"
  (or dir (setq dir (expand-file-name default-directory)))
  (let ((thefile (expand-file-name file dir)))
    (if (file-exists-p thefile)
        thefile
      (setq pdir (directory-file-name (file-name-directory dir)))
      (if (string= pdir dir)
          nil
        (simpletest-find-top-directory file pdir)))))

(defun simpletest-find-file-cached (class)
  "Find class name from cached file"
  (let ((file (simpletest-find-top-directory simpletest-class-cache-file)))
    (unless file
      (let ((dir (read-directory-name "Project root directory: ")))
        (simpletest-build-cache-file dir)))
      )
  )

(defun simpletest-find-source-file (class)
  "Get source file for `class'"
  (funcall (car simpletest-find-file-functions) class))

(defun simpletest-find-test-file (test-class)
  "Get test file for `test-class'"
  (funcall (cdr simpletest-find-file-functions) class))

(defun simpletest-create-test ()
  "Create test method for current class"
  (interactive)
  )

(defun simpletest-switch ()
  "Switch between test file and source file"
  (interactive)
  )

(provide 'simpletest)
;;; simpletest.el ends here
