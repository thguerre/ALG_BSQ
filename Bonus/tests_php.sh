

errors=0
counter=1
failedList=""

for f in mouli_maps/*
do
    echo "TEST #$counter - $(echo $f | sed 's/mouli_maps\///')"
    fs=$(echo $f | sed s/mouli_maps/mouli_maps_solved/)
    php ../bsq.php $f > output
    if [[ $(cmp output $fs) ]]; then
        echo -e "FAILED\n"
        errors=$((errors+1))
        failedList="$failedList #$counter - $f\n"
    else
        echo -e "PASSED\n"
    fi

    counter=$((counter+1))
done

rm output

if [[ "$errors" -eq "0" ]]; then
    echo "ALL TEST PASSED !"
else
    echo "FAILED $errors TESTS:"
    echo -ne "$failedList"
fi