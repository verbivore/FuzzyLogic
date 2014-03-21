# double leading whitespace: ../utl/gitsed.sh '^\( *\)' '&&'
echo parms=$*
find="$1"; shift
replace="$1"; shift
echo files=$*
git ls-files -z $* | xargs -0 sed -i -e "s/$find/$replace/g"
