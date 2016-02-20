<?php

class tree
{
    public $left;
    public $right;
    public $val;

    public function __construct( $val )
    {
        $this->right = NULL;
        $this->left = NULL;
        $this->val = $val;
    }

    public function set_left( $tree )
    {
        $this->left = $tree;
    }

    public function set_right( $tree )
    {
        $this->right = $tree;
    }

    public function result()
    {
        if( is_numeric( $this->val ) )
        {
            return $this->val;
        }

        if( $this->right == NULL AND $this->left == NULL )
        {
            return $this->val;
        }

        if( $this->val == '+' )
        {
            return $this->left->result() + $this->right->result();
        }

        if( $this->val == '-' )
        {
            return $this->left->result() - $this->right->result();
        }

        if( $this->val == '*' )
        {
            return $this->left->result() * $this->right->result();
        }

        if( $this->val == '/' )
        {
            return $this->left->result() / $this->right->result();
        }
    }
}

class MathParser
{
    private $tree;
    private $statement;
    private $result;

    public function __construct( $statement )
    {
        $this->statement = trim( $statement );
        $this->tree = $this->parse( $this->statement );
        $this->result = $this->tree->result();
    }

    public function result()
    {
        return $this->result;
    }

    protected function parse( $statement )
    {
        $statement = trim( $statement );
        if( empty( $statement ) OR is_numeric( $statement ) )
        {
            return new tree( $statement );
        } 

        $statement = str_split( $statement );
        $brackets = 0;
        $plus_found = false;
        $minus_found = false;

        $left = '';
        $right = '';

        foreach( $statement as $character )
        {
            if( $character == '(' )
            {
                $brackets++;
            }
            else if( $character == ')' )
            {
                $brackets--;
            }
            else if( $character == '+' AND $plus_found == false AND $minus_found == false AND $brackets == 0 )
            {
                $plus_found = true;
                continue;
            }
            else if( $character == '-' AND $plus_found == false AND $minus_found == false AND $brackets == 0 )
            {
                $minus_found = true;
                continue;
            }

            if( $plus_found == false AND $minus_found == false )
            {
                $left .= $character;
            }
            else if( $plus_found == true OR $minus_found == true )
            {
                $right .= $character;
            }
        }

        if( $plus_found == true )
        {
            $tree = new tree( '+' );
            $tree->set_left( $this->parse( $left ) );
            $tree->set_right( $this->parse( $right ) );
            return $tree;
        }
        else if( $minus_found == true )
        {
            $tree = new tree( '-' );
            $tree->set_left( $this->parse( $left ) );
            $tree->set_right( $this->parse( $right ) );
            return $tree;
        }

        $left = '';
        $right = '';
        $brackets = 0;
        $mult_found = false;
        $div_found = false;

        foreach( $statement as $character )
        {
            if( $character == '(' )
            {
                $brackets++;
            }
            else if( $character == ')' )
            {
                $brackets--;
            }

            if( $brackets == 0 AND $mult_found == false AND $div_found == false )
            {
                if( $character == '*' )
                {
                    $mult_found = true;
                    continue;
                }
                else if( $character == '/' )
                {
                    $div_found = true;
                    continue;
                }
            }

            if( $mult_found == false AND $div_found == false )
            {
                $left .= $character;
            }
            else
            {
                $right .= $character;
            }
        }

        if( $mult_found == true )
        {
            $tree = new tree( '*' );
            $tree->set_left( $this->parse( $left ) );
            $tree->set_right( $this->parse( $right ) );
            return $tree;
        }
        else if( $div_found == true )
        {
            $tree = new tree( '/' );
            $tree->set_left( $this->parse( $left ) );
            $tree->set_right( $this->parse( $right ) );
            return $tree;
        }

        $statement[0] = '';
        $statement[count($statement) - 1] = '';

        return $this->parse( trim( implode( $statement ) ) );

        return new tree( implode( $statement ) );
    }
}
