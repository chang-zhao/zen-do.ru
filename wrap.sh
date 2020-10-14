echo "Copy directory structure from 'in' to 'out':";
find ./in -type d | while read i;
do
    if [ ! -d "${i/in/out}" ]; then
        mkdir "${i/in/out}"
        echo "${i/in/out}";
    fi
done
echo "Wrap files:";
find ./in -name "*" | while read i;
do
    echo "${i/in/out}";
    cat ./tpl/header.html "$i" ./tpl/footer.html >"${i/in/out}"
    git add "${i/in/out}"
done
git config user.name "chang-zhao"
git commit . -m "Wrapping"
git push origin main
