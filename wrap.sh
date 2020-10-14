echo "Copy directory structure from 'in' to 'docs':";
find ./in -type d | while read i;
do
    if [ ! -d "${i/in/docs}" ]; then
        mkdir "${i/in/docs}"
        echo "${i/in/docs}";
    fi
done
echo "Wrap files:";
find ./in -type f -name "*" | while read i;
do
    echo "${i/in/docs}";
    cat ./tpl/header.html "$i" ./tpl/footer.html >"${i/in/docs}"
    git add "${i/in/docs}"
done
git config user.name "chang-zhao"
git commit . -m "Wrapping"
git push origin main
