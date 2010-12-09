import fix_path
import unittest
import jd

class TestChar(unittest.TestCase):
    def testSlice(self):
        a = jd.Char(3, 2, data=[[0,1],
                                [1,1],
                                [1,0]])
        s = a.slice(0, 1)
        self.assertEquals(s.data, [0, 1, 1])

    def testSig(self):
        a = jd.create_pattern("""
oooo                                                                       
    ooooooo                                                                
    ooo ooooooooo                                                          
    ooo ooo      oooo  oooooo   oooo    oooo        oooo    oooo           
    ooo ooo       oo       oo  oo  oo  oo  oo      oo  oo  oo  oo          
     ooooo       ooo      oo   oo  oo  oo  oo      oo  oo  oo  oo          
      ooo       o oo      oo    oooo    oooo       oo  oo  oo  oo          
    ooooooo    o  oo     oo    oo  oo  oo  oo      oo  oo  oo  oo          
      ooo      oooooo    oo    oo  oo  oo  oo      oo  oo  oo  oo          
      ooo         oo    oo     oo  oo  oo  oo  oo  oo  oo  oo  oo          
     ooooo        oo    oo      oooo    oooo   oo   oooo    oooo           
""")
        self.assertEquals(a.sig(), [1, 1, 1, 1, 5, 7, 10, 7, 10, 7, 5, 1, 1, 1, 1, 3, 3, 3, 8, 8, 2, 0, 0, 1, 3, 5, 5, 4, 2, 0, 0, 5, 8, 3, 3, 8, 5, 0, 0, 5, 8, 3, 3, 8, 5, 0, 0, 2, 2, 0, 0, 6, 8, 2, 2, 8, 6, 0, 0, 6, 8, 2, 2, 8, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0])

if __name__ == '__main__':
    unittest.main()

        
