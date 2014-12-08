ls | grep -v "conf" | while read filename
do
rm -R $filename
done
