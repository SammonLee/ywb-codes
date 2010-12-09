import fix_path
import unittest
import jd
import os
import logging

logging.basicConfig(level=logging.DEBUG,format="%(asctime)s[%(levelname)s] %(message)s",datefmt="%Y-%m-%d %H:%M:%S")
images_dir = os.path.join(os.path.split(__file__)[0], '../images')

class TestJd(unittest.TestCase):
    def testCreatePattern(self):
        rep = """
 o
oo
 o
"""
        p = jd.create_pattern(rep)
        self.assertEquals(p.rows, 3)
        self.assertEquals(p.cols, 2)
        self.assertEquals(str(p), rep[1:])

    def testRead(self):
        global images_dir
        chars = jd.read(os.path.join(images_dir, '4788.png'))
        self.assertEquals(len(chars), 6)

    def testParse(self):
        for file in os.listdir(images_dir):
            basename = os.path.splitext(file)[0]
            res = jd.parse(os.path.join(images_dir, file))
            logging.debug('parse %s => %s' % (file, res))
            self.assertEquals(res, basename+'00')

if __name__ == '__main__':
    unittest.main()
