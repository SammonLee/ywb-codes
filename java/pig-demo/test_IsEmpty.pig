REGISTER myudfs.jar;
A = LOAD 'data/student.txt' AS (name: chararray, sub, gpa: float);
C = GROUP A BY name;
B = FILTER C BY not myudfs.IsEmpty(A);
DUMP B;
