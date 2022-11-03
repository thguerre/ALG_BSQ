


for f in mouli_maps/*
do
    echo "$(echo $f | sed s/mouli_maps//)"
    fs=$(echo $f | sed s/mouli_maps/mouli_maps_solved/)
    echo "$fs"
    ./bsq $f > output
    diff output $fs
    echo ""
done