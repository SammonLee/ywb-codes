A = LOAD 'data/data' AS (url:chararray,outlink:chararray);
B = GROUP A BY url;
X = foreach B {
       FA= FILTER A BY outlink == 'www.xyz.org';
       PA = FA.outlink;
       DA = DISTINCT PA;
       GENERATE group, COUNT(DA);
};
DUMP A;
DUMP B;
DUMP X;
