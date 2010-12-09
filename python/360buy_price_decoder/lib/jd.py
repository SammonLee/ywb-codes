import png
import os
import itertools

class TwoDimArray:
    def __init__(self, rows, cols, fill=None, data=None):
        self.rows = rows
        self.cols = cols
        self.data = map(fill if callable(fill) else (lambda(i): fill), range(0, rows*cols))
        if data != None:
            self.setData(data)

    def setData(self, data):
        if len(data) == len(self.data):
            self.data = data
        else:
            for x in range(0, self.cols):
                for y in range(0, self.rows):
                    self.set(x, y, data[y][x])

    def getX(self, index):
        if index < len(self.data):
            return (index-self.getY(index)) / self.rows

    def getY(self, index):
        if index < len(self.data):
            return index % self.rows

    def getIndex(self, x, y):
        return self.rows*x + y

    def get(self, x, y):
        return self.data[self.getIndex(x,y)]

    def set(self, x, y, val):
        self.data[self.getIndex(x, y)] = val

    def slice(self, offsetx, length):
        if offsetx < 0:
            offsetx = self.rows-offsetx
        if not (offsetx >= 0 and offsetx < self.cols):
            raise IndexError
        start = self.rows*offsetx
        end = self.rows*min(self.cols, offsetx+length)
        return self.__class__(self.rows, length, data=self.data[start:end])

class Char(TwoDimArray):
    def sig(self):
        s = []
        for x in range(0, self.cols):
            s.append(sum([self.get(x,y) for y in range(0, self.rows)]))
        return s

class Pattern(Char):
    char_on = 'o'
    char_off = ' '

    def __init__(self, cols, rows, char=None, data=None, fill=None):
        Char.__init__(self, cols, rows, fill=(lambda(i): [0, 0, 0]), data=data)
        self.char = char

    def sig(self):
        if not hasattr(self, 'sig_arr'):
            self.sig_arr = Char.sig(self)
        return self.sig_arr

    def set(self, x, y, val):
        el = Char.get(self, x, y)
        el[0] = val
        el[ 2 if val else 1 ] += 1

    def get(self, x, y):
        return Char.get(self, x, y)[0]

    def addWitness(self, p):
        if p.rows != self.rows:
            return
        for x in range(0, min(p.cols, self.cols)):
            for y in range(0, p.rows):
                self.set(x, y, p.get(x, y))

    def fix(self):
        total = self.data[0][1] + self.data[0][2]
        valid_cols = range(0, self.cols)
        for index in range(0, len(self.data)):
            el = self.data[index]
            if el[1]+el[2] != total :
                valid_cols[self.getX(index)] = None
            else:
                el[0] = 0 if el[1]>el[2] else 1
        if None in valid_cols:
            col = valid_cols.index(None)
            p = self.slice(0, col)
            self.cols = p.cols
            self.data = p.data

    def getDistance(self, p):
        if p.sig() == self.sig():
            return 0
        mis, dis, start = (0, 0, -1)
        for i in range(0, p.cols):
            if i < self.cols:
                mis=self.colMisMatch(p, 0, i)
                if mis<2:
                    start = i
                    break
        if start != -1:
            dis = mis
            for i in range(1, self.cols):
                mis = self.colMisMatch(p, i, start+i)
                if mis > 1:
                    if i > 2 :
                        break
                    else:
                        return 1
                dis += mis
            return float(dis)/len(self.data)
        else:
            return 1

    def colMisMatch(self, p, mx, tx):
        if tx >= p.cols:
            return p.cols
        mis = 0
        for i in range(0, self.rows):
            if self.get(mx,i) != p.get(tx,i):
                mis+=1
        return mis

    def __str__(self):
        s = ''
        for y in range(0, self.rows):
            for x in range(0, self.cols):
                el = Char.get(self, x, y)

                s += self.char_on if self.get(x,y) else self.char_off
            s += "\n"
        return s

class PatternTrait:
    def __init__(self, rows=0, cols=0):
        self.rows = rows
        self.cols = cols
        self.data = {}

    def addWitness(self, num, char):
        if not self.data.has_key(num):
            self.data[num] = Pattern(self.rows, self.cols, num)
        self.data[num].addWitness(char)

    def fix(self):
        for p in self.data.values():
            p.fix()

    def parseFile(self, file):
        chars = ''
        for c in read(file):
            matches = ['', 1]
            for pattern in self.data.values():
                dist = pattern.getDistance(c)
                if dist < matches[1]:
                    matches[0] = pattern.char
                    matches[1] = dist
            # print matches
            chars += matches[0]
        return chars

def create_pattern(s, char=None):
    if s[0] == '\n': s=s[1:]
    if s[-1] == '\n': s=s[0:-1]
    lines = s.split("\n")
    p = Pattern(len(lines), len(lines[0]), char=char)
    for x in range(0, p.cols):
        for y in range(0, p.rows):
            p.set(x, y, 1 if lines[y][x] == Pattern.char_on else 0)
    return p

def learning(path):
    trait = PatternTrait(rows=12, cols=6)
    for file in os.listdir(path):
        basename, ext = os.path.splitext(file)
        if ext == '.png' :
            chars = read(os.path.join(path, file))
            # print 'learning from ' + os.path.join(path, file)
            if len(chars) == (len(basename)+2):
                for i in range(0, len(basename)):
                    trait.addWitness(basename[i], chars[i])
    trait.fix()
    return trait

def read(file):
    img = png.Reader(filename=file)
    width, height, pixels, metadata = img.read();
    pixel_byte_width = 4 if metadata['alpha'] else 3
    pixels = zip(pixels)
    points = Char(height, width)
    for x in range(0, width):
        for y in range(0, height):
            (r,g,b,a) = pixels[y][0][x*pixel_byte_width:(x+1)*pixel_byte_width]
            points.set(x, y, 0 if (r==0xff and g==0xff and b==0xff) else 1);
    chars = split(points)
    chars.pop(0)
    chars.pop(-3)
    return chars

def split(points):
    sig = points.sig()
    curr, chars = [], [];
    for x in range(0, points.cols):
        if sig[x] <= 1:
            l = len(curr)
            if l != 0 :
                # print 'x: %d l: %d' % (x, l)
                c = points.slice(x-l, l)
                chars.append(c)
                curr = []
        else:
            curr.append(sig[x])
    return chars

trait = PatternTrait()
trait.data["0"] = create_pattern("""
      
      
      
 oooo 
oo  oo
oo  oo
oo  oo
oo  oo
oo  oo
oo  oo
 oooo 
      
""", "0")
trait.data["1"] = create_pattern("""
   
   
   
 oo
ooo
 oo
 oo
 oo
 oo
 oo
ooo
   
""", "1")
trait.data["2"] = create_pattern("""
      
      
      
 oooo 
oo  oo
    oo
   oo 
  oo  
 oo   
oo    
oooooo
      
""", "2")
trait.data["3"] = create_pattern("""
      
      
      
 oooo 
oo  oo
    oo
  ooo 
    oo
    oo
oo  oo
 oooo 
      
""", "3")
trait.data["4"] = create_pattern("""
     
     
     
    o
   oo
  ooo
 o oo
o  oo
ooooo 
   oo
   oo
     
""", "4")
trait.data["5"] = create_pattern("""
      
      
      
 ooooo
 oo   
 oo   
 ooooo
oooooo
    oo
oo  oo
 oooo 
      
""", "5")
trait.data["6"] = create_pattern("""
      
      
      
  ooo 
 oo   
oo    
ooooo 
oo  oo
oo  oo
oo  oo
 oooo 
      
""", "6")
trait.data["7"] = create_pattern("""
     
     
     
ooooo
   oo
  oo 
  oo 
 oo  
 oo  
oo   
oo   
     
""", "7")
trait.data["8"] = create_pattern("""
      
      
      
 oooo 
oo  oo
oo  oo
 oooo 
oo  oo
oo  oo
oo  oo
 oooo 
      
""", "8")
trait.data["9"] = create_pattern("""
      
      
      
 oooo 
oo  oo
oo  oo
oo  oo
 ooooo
    oo
   oo 
 ooo  
      
""", "9")

def parse(file):
    global trait
    return trait.parseFile(file)

if __name__ == '__main__':
    __import__("os")
    images_dir = os.path.join(os.path.split(__file__)[0], '../images')
    trait = learning(images_dir)
    for num in sorted(trait.data):
        print 'trait.data["%s"] = create_pattern("""\n%s""", "%s")' % (num, trait.data[num], num)

