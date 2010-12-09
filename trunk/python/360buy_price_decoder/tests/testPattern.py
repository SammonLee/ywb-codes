import fix_path
import unittest
import jd

class TestPattern(unittest.TestCase):
    def testIsMatch(self):
        p = jd.create_pattern("""
 o
oo
 o
""")
        self.assertEquals(p.getDistance(p), 0)
        p2 = jd.create_pattern("""
 o
oo
  
""")
        self.assertEquals(p.getDistance(p2), 1.0/6)
        p2 = jd.create_pattern("""
 o
o 
  
""")
        self.assertEquals(p.getDistance(p2), 1)

    def testAddWitness(self):
        p = jd.Pattern(3, 2)
        p.addWitness(jd.create_pattern("""
 o
oo
 o
"""))
        p.addWitness(jd.create_pattern("""
 o
oo
  
"""))
        self.assertEquals(p.data, [[0, 2, 0], [1, 0, 2], [0, 2, 0], [1, 0, 2], [1, 0, 2], [0, 1, 1]])

    def testFix(self):
        p = jd.Pattern(3, 2)
        p.addWitness(jd.create_pattern("""
 o
oo
 o
"""))
        p.addWitness(jd.create_pattern("""
 o
oo
  
"""))
        p.addWitness(jd.create_pattern("""
 oo
 oo
 oo
"""))
        p.fix()
        self.assertEquals(str(p),""" o
oo
 o
""");

    def testSum(self):
        points = jd.create_pattern("""
 o
oo
 o
""")
        sumy = [];
        for x in range(0, points.cols):
            s = 0
            for y in range(0, points.rows):
                s+= points.get(x,y)
            sumy.append(s)
        self.assertEquals(sumy,[1,3])

    def testIsMatchReal(self):
        p = jd.create_pattern("""      
      
      
 oooo 
oo  oo
    oo
  ooo 
    oo
    oo
oo  oo
 oooo 
      
""")
        self.assertEquals(p.getDistance(p), 0)

    def testSlice(self):
        p = jd.create_pattern("""      
      
      
 oooo 
oo  oo
    oo
  ooo 
    oo
    oo
oo  oo
 oooo 
      
""")
        s = p.slice(0, 2)
        s_str =  '''  
  
  
 o
oo
  
  
  
  
oo
 o
  
'''
        self.assertEquals(str(s),s_str)
        
if __name__ == '__main__':
    unittest.main()
