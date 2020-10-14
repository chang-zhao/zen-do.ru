echo "Copy directory structure from 'in' to 'out':";
find ./in -type d | while read i;
do
    if [ ! -d "${i/in/out}" ]; then
        mkdir "${i/in/out}"
        echo "${i/in/out}";
    fi
done
echo "Wrap files:";
find ./in -name "*.html" | while read i;
do
    echo "${i/in/out}";
    cat ./tpl/header.html "$i" ./tpl/footer.html >"${i/in/out}"
done
