REGISTER myudfs.jar;
A = LOAD 'data/student.txt' AS (name: chararray, sub, gpa: float);
B = FOREACH A GENERATE myudfs.UPPER(name);
DUMP B;
