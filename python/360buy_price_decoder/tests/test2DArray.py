import fix_path
import unittest
from jd import TwoDimArray

class Test2DArray(unittest.TestCase):
    def testInitWithRowsAndCols(self):
        a = TwoDimArray(3, 2)
        self.assertEquals(a.rows, 3)
        self.assertEquals(a.cols, 2)
        self.assertEquals(a.data, [None for i in range(0,6)])

    def testInitWithFill(self):
        a = TwoDimArray(3, 2, fill=0)
        self.assertEquals(a.data, [0 for i in range(0,6)])

    def testInitWithFillLambda(self):
        a = TwoDimArray(3, 2, fill=(lambda(i): [1]))
        self.assertEquals(a.data, [[1] for i in range(0,6)])

    def testInitWithData(self):
        a = TwoDimArray(3, 2, data=[[0,1],[1,1],[1,0]])
        self.assertEquals(a.data, [0, 1, 1, 1, 1, 0])

    def testInitWithData2(self):
        a = TwoDimArray(3, 2, data=[0, 1, 1, 1, 1, 0])
        self.assertEquals(a.data, [0, 1, 1, 1, 1, 0])

    def testGet(self):
        a = TwoDimArray(3, 2, data=[[0,1],[1,1],[1,0]])
        self.assertEquals(a.get(0, 0), 0);
        self.assertEquals(a.get(1, 2), 0);

    def testSet(self):
        a = TwoDimArray(3, 2, data=[[0,1],[1,1],[1,0]])
        a.set(1, 2, 1)
        self.assertEquals(a.data, [0, 1, 1, 1, 1, 1])

    def testSlice(self):
        a = TwoDimArray(3, 2, data=[[0,1],[1,1],[1,0]])
        s = a.slice(0, 1)
        self.assertEquals(s.data, [0, 1, 1])
        self.assertEquals(s.rows, 3)
        self.assertEquals(s.cols, 1)
        self.assertEquals(a.slice(1,1).data, [1, 1, 0])
        self.assertEquals(a.slice(0, 2).data, a.data)

if __name__ == '__main__':
    unittest.main()
