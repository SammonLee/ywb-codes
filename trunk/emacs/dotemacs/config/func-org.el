;; table convert
;;;###autoload 
(defun ywb-org-table-convert-region (beg end wspace)
  (interactive "r\nP")
  (require 'org)
  (when (= beg (point-min))
    (save-excursion
      (goto-char beg)
      (insert "\n")
      (setq beg (1+ beg))))
  (or (eq major-mode 'org-mode) (org-mode))
  (org-table-convert-region beg end wspace))
;;;###autoload 
(defun ywb-org-table-export-here (beg end)
  (interactive "r")
  (require 'org)
  (let ((buf (generate-new-buffer "*temp*"))
        (table (delete-and-extract-region beg end)))
    (with-current-buffer buf
      (insert table)
      (goto-char (point-min))
      (while (re-search-forward "^[ \t]*|[ \t]*" nil t)
        (replace-match "" t t)
        (end-of-line 1))
      (goto-char (point-min))
      (while (re-search-forward "[ \t]*|[ \t]*$" nil t)
        (replace-match "" t t)
        (goto-char (min (1+ (point)) (point-max))))
      (goto-char (point-min))
      (while (re-search-forward "^-[-+]*$" nil t)
        (replace-match "")
        (if (looking-at "\n")
            (delete-char 1)))
      (goto-char (point-min))
      (while (re-search-forward "[ \t]*|[ \t]*" nil t)
        (replace-match "\t" t t))
      (setq table (buffer-string))
      (kill-buffer buf))
    (insert table)))
;;;###autoload
(defun ywb-org-table-sort-lines (reverse beg end numericp)
  (interactive "P\nr\nsSorting method: [n]=numeric [a]=alpha: ")
  (setq numericp (string-match "[nN]" numericp))
  (org-table-align)
  (save-excursion
    (setq beg (progn (goto-char beg) (line-beginning-position))
          end (progn (goto-char end) (line-end-position))))
  (let ((col (org-table-current-column))
        (cmp (if numericp
                 (lambda (a b) (< (string-to-number a)
                                  (string-to-number b)))
               'string<)))
    (ywb-sort-lines-1 reverse beg end
                      (lambda (pos1 pos2)
                        (let ((dat1 (split-string (buffer-substring-no-properties
                                                   (car pos1) (cdr pos1))
                                                  "\\s-*|\\s-*"))
                              (dat2 (split-string (buffer-substring-no-properties
                                                   (car pos2) (cdr pos2))
                                                  "\\s-*|\\s-*")))
                          (funcall cmp (nth col dat1) (nth col dat2)))))
    (dotimes (i col) (org-table-next-field))))
;;;###autoload
(defun ywb-box-table (beg end)
  (interactive "r")
  (require 'org)
  (let ((tbl (delete-and-extract-region beg end)))
    (insert
     (substring 
      (with-temp-buffer
        (org-mode)
        (insert "\n")
        (setq beg (point))
        (insert tbl)
        (setq end (point))
        (ywb-org-table-convert-region beg end nil)
        (beginning-of-line 1) (open-line 1) (insert "|-")
        (org-cycle 1) (end-of-line 1) (insert "\n|-")
        (org-cycle 1) (goto-char (point-max)) (insert "|-")
        (org-cycle 1) (beginning-of-line 1) (kill-line)
        (buffer-string)) 1))))