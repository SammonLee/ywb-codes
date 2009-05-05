REGISTER myudfs.jar;
A = LOAD 'data/student.txt' AS (name: chararray, sub, gpa: float);
B = GROUP A BY name;
X = FOREACH B GENERATE group, myudfs.COUNT(A);
DUMP X;
