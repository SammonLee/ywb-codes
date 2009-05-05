REGISTER myudfs.jar;
A = LOAD 'data/student.txt' AS (name: chararray, sub, gpa: float);
B = foreach A generate flatten(myudfs.Swap(name, sub)), gpa;
C = foreach B generate $2;
D = limit B 20;
dump C;
dump D;
